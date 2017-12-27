<?php

namespace Duo\AdminBundle\Controller\Behavior;

use Doctrine\Common\Persistence\ObjectManager;
use Duo\AdminBundle\Controller\Listing\AbstractController;
use Duo\AdminBundle\Entity\Behavior\SortInterface;
use Duo\AdminBundle\Entity\Behavior\TreeInterface;
use Duo\AdminBundle\Entity\Behavior\VersionInterface;
use Duo\AdminBundle\Event\Behavior\SortEvent;
use Duo\AdminBundle\Event\Behavior\SortEvents;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait SortTrait
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
		 * @var AbstractController $this
		 */
		$repository = $this->getDoctrine()->getRepository($this->getEntityClassName());

		if (($entity = $repository->find($id)) === null)
		{
			return $this->entityNotFound($id, $request);
		}

		if (!$entity instanceof SortInterface)
		{
			return $this->sortableInterfaceNotImplemented($id, $request);
		}

		/**
		 * @var SortInterface $previousEntity
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
			$dispatcher->dispatch(SortEvents::SORT, new SortEvent($previousEntity));
			$dispatcher->dispatch(SortEvents::SORT, new SortEvent($entity));

			/**
			 * @var ObjectManager $em
			 */
			$em = $this->getDoctrine()->getManager();
			$em->persist($previousEntity);
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

		return $this->redirectToRoute("duo_admin_listing_{$this->getListType()}_index");
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
		 * @var AbstractController $this
		 */
		$repository = $this->getDoctrine()->getRepository($this->getEntityClassName());

		if (($entity = $repository->find($id)) === null)
		{
			return $this->entityNotFound($id, $request);
		}

		if (!$entity instanceof SortInterface)
		{
			return $this->sortableInterfaceNotImplemented($id, $request);
		}

		/**
		 * @var SortInterface $nextEntity
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
			$dispatcher->dispatch(SortEvents::SORT, new SortEvent($nextEntity));
			$dispatcher->dispatch(SortEvents::SORT, new SortEvent($entity));

			/**
			 * @var ObjectManager $em
			 */
			$em = $this->getDoctrine()->getManager();
			$em->persist($nextEntity);
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

		return $this->redirectToRoute("duo_admin_listing_{$this->getListType()}_index");
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
		 * @var AbstractController $this
		 */
		$repository = $this->getDoctrine()->getRepository($this->getEntityClassName());

		if ((!$entity = $repository->find($id)) === null)
		{
			return $this->entityNotFound($id, $request);
		}

		if (!$entity instanceof SortInterface)
		{
			return $this->sortableInterfaceNotImplemented($id, $request);
		}

		$criteria = [
			'weight' => $weight
		];

		// use parent entity
		if ($entity instanceof TreeInterface)
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
		if ($entity instanceof VersionInterface)
		{
			$criteria['version'] = $entity->getVersion();
		}

		/**
		 * @var SortInterface $currentEntity
		 */
		$currentEntity = $repository->findOneBy($criteria);

		/**
		 * @var ObjectManager $em
		 */
		$em = $this->getDoctrine()->getManager();

		/**
		 * @var TraceableEventDispatcher $dispatcher
		 */
		$dispatcher = $this->get('event_dispatcher');

		if ($currentEntity !== null)
		{
			$entity->setWeight($currentEntity->getWeight());
			$dispatcher->dispatch(SortEvents::SORT, new SortEvent($entity));

			$em->persist($entity);

			$weight = $currentEntity->getWeight();
			$currentEntity->setWeight($weight++);
			$dispatcher->dispatch(SortEvents::SORT, new SortEvent($currentEntity));

			$em->persist($currentEntity);

			/**
			 * @var SortInterface[] $entities
			 */
			$entities = $repository->findNextWeight($currentEntity, null);

			foreach ($entities as $entity)
			{
				$entity->setWeight($weight++);
				$dispatcher->dispatch(SortEvents::SORT, new SortEvent($entity));

				$em->persist($entity);
			}

			$em->flush();
		}
		else
		{
			$entity->setWeight($weight);
			$dispatcher->dispatch(SortEvents::SORT, new SortEvent($entity));

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

		return $this->redirectToRoute("duo_admin_listing_{$this->getListType()}_index");
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
		$interface = SortInterface::class;
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