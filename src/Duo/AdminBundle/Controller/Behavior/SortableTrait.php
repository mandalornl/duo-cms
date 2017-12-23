<?php

namespace Duo\AdminBundle\Controller\Behavior;

use Doctrine\Common\Persistence\ObjectManager;
use Duo\AdminBundle\Controller\AbstractAdminController;
use Duo\AdminBundle\Entity\Behavior\SortableInterface;
use Duo\AdminBundle\Entity\Behavior\TreeableInterface;
use Duo\AdminBundle\Entity\Behavior\VersionableInterface;
use Duo\AdminBundle\Event\Behavior\SortableEvent;
use Duo\AdminBundle\Event\Behavior\SortableEvents;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait SortableTrait
{
	/**
	 * Move entity up
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	protected function doMoveUpAction(Request $request, int $id)
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$repository = $this->getDoctrine()->getRepository($this->getEntityClassName());

		if (($entity = $repository->find($id)) === null)
		{
			return $this->entityNotFound($id, $request);
		}

		if (!$entity instanceof SortableInterface)
		{
			return $this->sortableInterfaceNotImplemented($id, $request);
		}

		/**
		 * @var SortableInterface $previousEntity
		 */
		$previousEntity = $repository->findPreviousWeight($entity);

		if ($previousEntity !== null)
		{
			$weight = $previousEntity->getWeight();
			$previousEntity->setWeight($entity->getWeight());
			$entity->setWeight($weight);

			/**
			 * @var TraceableEventDispatcher $dispatcher
			 */
			$dispatcher = $this->get('event_dispatcher');
			$dispatcher->dispatch(SortableEvents::PRE_SORT, new SortableEvent($previousEntity));
			$dispatcher->dispatch(SortableEvents::PRE_SORT, new SortableEvent($entity));

			/**
			 * @var ObjectManager $em
			 */
			$em = $this->getDoctrine()->getManager();
			$em->persist($previousEntity);
			$em->persist($entity);
			$em->flush();

			$dispatcher->dispatch(SortableEvents::POST_SORT, new SortableEvent($previousEntity));
			$dispatcher->dispatch(SortableEvents::POST_SORT, new SortableEvent($entity));
		}

		if ($request->getMethod() === 'post')
		{
			return new JsonResponse([
				'result' => [
					'success' => true
				]
			]);
		}

		return $this->redirectToRoute("duo_admin_{$this->getListType()}_list");
	}

	/**
	 * Move entity down
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	protected function doMoveDownAction(Request $request, int $id)
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$repository = $this->getDoctrine()->getRepository($this->getEntityClassName());

		if (($entity = $repository->find($id)) === null)
		{
			return $this->entityNotFound($id, $request);
		}

		if (!$entity instanceof SortableInterface)
		{
			return $this->sortableInterfaceNotImplemented($id, $request);
		}

		/**
		 * @var SortableInterface $nextEntity
		 */
		$nextEntity = $repository->findNextWeight($entity);

		if ($nextEntity !== null)
		{
			$weight = $nextEntity->getWeight();
			$nextEntity->setWeight($entity->getWeight());
			$entity->setWeight($weight);

			/**
			 * @var TraceableEventDispatcher $dispatcher
			 */
			$dispatcher = $this->get('event_dispatcher');
			$dispatcher->dispatch(SortableEvents::PRE_SORT, new SortableEvent($nextEntity));
			$dispatcher->dispatch(SortableEvents::PRE_SORT, new SortableEvent($entity));

			/**
			 * @var ObjectManager $em
			 */
			$em = $this->getDoctrine()->getManager();
			$em->persist($nextEntity);
			$em->persist($entity);
			$em->flush();

			$dispatcher->dispatch(SortableEvents::POST_SORT, new SortableEvent($nextEntity));
			$dispatcher->dispatch(SortableEvents::POST_SORT, new SortableEvent($entity));
		}

		if ($request->getMethod() === 'post')
		{
			return new JsonResponse([
				'result' => [
					'success' => true
				]
			]);
		}

		return $this->redirectToRoute("duo_admin_{$this->getListType()}_list");
	}

	/**
	 * Move entity to
	 *
	 * @param Request $request
	 * @param int $id
	 * @param int $weight
	 * @param int $parentId [optional]
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	protected function doMoveToAction(Request $request, int $id, int $weight, int $parentId = null)
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$repository = $this->getDoctrine()->getRepository($this->getEntityClassName());

		if ((!$entity = $repository->find($id)) === null)
		{
			return $this->entityNotFound($id, $request);
		}

		if (!$entity instanceof SortableInterface)
		{
			return $this->sortableInterfaceNotImplemented($id, $request);
		}

		$criteria = [
			'weight' => $weight
		];

		// use parent entity
		if ($entity instanceof TreeableInterface)
		{
			if ($parentId !== null && ($parent = $repository->find($parentId)) !== null)
			{
				$criteria['parent'] = $parent;
			}
			else
			{
				if (($parent = $entity->getParent()) !== null)
				{
					$criteria['parent'] = $parent;
				}
			}
		}

		// use latest version
		if ($entity instanceof VersionableInterface)
		{
			$criteria['version'] = $entity->getVersion();
		}

		/**
		 * @var SortableInterface $currentEntity
		 */
		$currentEntity = $repository->findOneBy($criteria);

		/**
		 * @var ObjectManager $em
		 */
		$em = $this->getDoctrine()->getManager();

		if ($currentEntity !== null)
		{
			$entity->setWeight($currentEntity->getWeight());
			$em->persist($entity);

			$weight = $currentEntity->getWeight();
			$currentEntity->setWeight($weight++);

			$em->persist($currentEntity);

			/**
			 * @var SortableInterface[] $entities
			 */
			$entities = $repository->findNextWeight($currentEntity, null);

			foreach ($entities as $entity)
			{
				$entity->setWeight($weight++);
				$em->persist($entity);
			}

			$em->flush();
		}
		else
		{
			$entity->setWeight($weight);
			$em->persist($entity);
			$em->flush();
		}

		if ($request->getMethod() === 'post')
		{
			return new JsonResponse([
				'result' => [
					'success' => true
				]
			]);
		}

		return $this->redirectToRoute("duo_admin_{$this->getListType()}_list");
	}

	/**
	 * Sortable interface not implemented
	 *
	 * @param int $id
	 * @param Request $request
	 *
	 * @return Response|JsonResponse
	 */
	private function sortableInterfaceNotImplemented(int $id, Request $request)
	{
		$interface = SortableInterface::class;
		$error = "Entity of type '{$this->getEntityClassName()}' with id '{$id}' doesn't implement '{$interface}'";

		if ($request->getMethod() === 'post')
		{
			return new JsonResponse([
				'result' => [
					'success' => false,
					'error' => $error
				]
			]);
		}

		return new Response($error, 500);
	}

	/**
	 * Move entity up
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	abstract public function moveUpAction(Request $request, int $id);

	/**
	 * Move entity down
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	abstract public function moveDownAction(Request $request, int $id);

	/**
	 * Move entity to
	 *
	 * @param Request $request
	 * @param int $id
	 * @param int $weight
	 * @oaram int $parentId [optional]
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	abstract public function moveToAction(Request $request, int $id, int $weight, int $parentId = null);
}