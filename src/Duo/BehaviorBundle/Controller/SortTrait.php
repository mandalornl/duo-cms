<?php

namespace Duo\BehaviorBundle\Controller;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\AdminBundle\Controller\AbstractListingController;
use Duo\BehaviorBundle\Entity\SortInterface;
use Duo\BehaviorBundle\Entity\TreeInterface;
use Duo\BehaviorBundle\Entity\RevisionInterface;
use Duo\BehaviorBundle\Event\SortEvent;
use Duo\BehaviorBundle\Event\SortEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

trait SortTrait
{
	/**
	 * Move entity up
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws AnnotationException
	 */
	protected function doMoveUpAction(Request $request, int $id)
	{
		/**
		 * @var AbstractListingController|SortTrait $this
		 */
		return $this->handleBasicMovementRequest($request, $id, function(SortInterface $entity)
		{
			/**
			 * @var SortInterface $previousEntity
			 */
			$previousEntity = $this->getDoctrine()->getRepository($this->getEntityClassName())->findPreviousWeight($entity);

			if ($previousEntity !== null)
			{
				$weight = $previousEntity->getWeight();
				$previousEntity->setWeight($entity->getWeight());
				$entity->setWeight($weight);

				/**
				 * @var EventDispatcherInterface $dispatcher
				 */
				$dispatcher = $this->get('event_dispatcher');
				$dispatcher->dispatch(SortEvents::SORT, new SortEvent($entity, $previousEntity));

				/**
				 * @var ObjectManager $em
				 */
				$em = $this->getDoctrine()->getManager();
				$em->persist($previousEntity);
				$em->persist($entity);
				$em->flush();
			}
		});
	}

	/**
	 * Move entity down
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws AnnotationException
	 */
	protected function doMoveDownAction(Request $request, int $id)
	{
		/**
		 * @var AbstractListingController|SortTrait $this
		 */
		return $this->handleBasicMovementRequest($request, $id, function(SortInterface $entity)
		{
			/**
			 * @var SortInterface $nextEntity
			 */
			$nextEntity = $this->getDoctrine()->getRepository($this->getEntityClassName())->findNextWeight($entity);

			if ($nextEntity !== null)
			{
				$weight = $nextEntity->getWeight();
				$nextEntity->setWeight($entity->getWeight());
				$entity->setWeight($weight);

				/**
				 * @var EventDispatcherInterface $dispatcher
				 */
				$dispatcher = $this->get('event_dispatcher');
				$dispatcher->dispatch(SortEvents::SORT, new SortEvent($entity, $nextEntity));

				/**
				 * @var ObjectManager $em
				 */
				$em = $this->getDoctrine()->getManager();
				$em->persist($nextEntity);
				$em->persist($entity);
				$em->flush();
			}
		});
	}

	/**
	 * Handle basic movement request
	 *
	 * @param Request $request
	 * @param int $id
	 * @param \Closure $callback
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws AnnotationException
	 */
	private function handleBasicMovementRequest(Request $request, int $id, \Closure $callback)
	{
		/**
		 * @var AbstractListingController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityFqcn());

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		if (!$entity instanceof SortInterface)
		{
			return $this->sortInterfaceNotImplemented($request, $id);
		}

		call_user_func($callback, $entity);

		/// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return new JsonResponse([
				'result' => [
					'success' => true
				]
			]);
		}

		return $this->redirectToReferer(
			$this->generateUrl("{$this->getRoutePrefix()}_index"),
			$request
		);
	}

	/**
	 * Move entity to
	 *
	 * @param Request $request
	 * @param int $id
	 * @param int $weight
	 * @param int $parentId [optional]
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws AnnotationException
	 */
	protected function doMoveToAction(Request $request, int $id, int $weight, int $parentId = null)
	{
		/**
		 * @var AbstractListingController $this
		 */
		$repository = $this->getDoctrine()->getRepository($this->getEntityFqcn());

		if ((!$entity = $repository->find($id)) === null)
		{
			return $this->entityNotFound($request, $id);
		}

		if (!$entity instanceof SortInterface)
		{
			return $this->sortInterfaceNotImplemented($request, $id);
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

		// use latest revision
		if ($entity instanceof RevisionInterface)
		{
			$criteria['revision'] = $entity->getRevision();
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
		 * @var EventDispatcherInterface $dispatcher
		 */
		$dispatcher = $this->get('event_dispatcher');

		if ($currentEntity !== null)
		{
			$entity->setWeight($currentEntity->getWeight());
			$em->persist($entity);

			$weight = $currentEntity->getWeight();
			$currentEntity->setWeight($weight++);
			$dispatcher->dispatch(SortEvents::SORT, new SortEvent($entity, $currentEntity));

			$em->persist($currentEntity);

			/**
			 * @var SortInterface[] $entities
			 */
			$entities = $repository->findNextWeight($currentEntity, null);

			foreach ($entities as $entity)
			{
				$entity->setWeight($weight++);
				$dispatcher->dispatch(SortEvents::SORT, new SortEvent($entity, $currentEntity));

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

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return new JsonResponse([
				'result' => [
					'success' => true
				]
			]);
		}

		return $this->redirectToReferer(
			$this->generateUrl("{$this->getRoutePrefix()}_index"),
			$request
		);
	}

	/**
	 * Sortable interface not implemented
	 *
	 * @param int $id
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	private function sortInterfaceNotImplemented(Request $request, int $id): JsonResponse
	{
		$interface = SortInterface::class;
		$error = "Entity '{$this->getEntityClassName()}::{$id}' doesn't implement '{$interface}'";

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return new JsonResponse([
				'result' => [
					'success' => false,
					'error' => $error
				]
			]);
		}

		throw $this->createNotFoundException($error);
	}
}