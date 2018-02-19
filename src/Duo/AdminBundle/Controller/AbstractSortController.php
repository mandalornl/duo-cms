<?php

namespace Duo\AdminBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Duo\BehaviorBundle\Entity\SortInterface;
use Duo\BehaviorBundle\Entity\TreeInterface;
use Duo\BehaviorBundle\Event\SortEvent;
use Duo\BehaviorBundle\Event\SortEvents;
use Duo\BehaviorBundle\Repository\SortTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractSortController extends AbstractController
{
	/**
	 * Move entity up
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	protected function doMoveUpAction(Request $request, int $id)
	{
		return $this->handleMoveUpOrDownRequest($request, $id, function(SortInterface $entity)
		{
			$em = $this->getDoctrine()->getManager();

			/**
			 * @var SortTrait $repository
			 */
			$repository = $em->getRepository($this->getEntityClass());

			$previousEntity = $repository->findPrevToSort($entity);

			if ($previousEntity !== null)
			{
				$weight = $previousEntity->getWeight();
				$previousEntity->setWeight($entity->getWeight());
				$entity->setWeight($weight);

				// dispatch sort event
				$this->get('event_dispatcher')->dispatch(SortEvents::SORT, new SortEvent($entity, $previousEntity));

				$em->persist($previousEntity);
				$em->persist($entity);
				$em->flush();
			}
		});
	}

	/**
	 * Move entity up
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 */
	abstract public function moveUpAction(Request $request, int $id);

	/**
	 * Move entity down
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	protected function doMoveDownAction(Request $request, int $id)
	{
		return $this->handleMoveUpOrDownRequest($request, $id, function(SortInterface $entity)
		{
			$em = $this->getDoctrine()->getManager();

			/**
			 * @var SortTrait $repository
			 */
			$repository = $em->getRepository($this->getEntityClass());

			$nextEntity = $repository->findNextToSort($entity);

			if ($nextEntity !== null)
			{
				$weight = $nextEntity->getWeight();
				$nextEntity->setWeight($entity->getWeight());
				$entity->setWeight($weight);

				// dispatch sort event
				$this->get('event_dispatcher')->dispatch(SortEvents::SORT, new SortEvent($entity, $nextEntity));

				$em->persist($nextEntity);
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
	 */
	abstract public function moveDownAction(Request $request, int $id);

	/**
	 * Handle move up or down request
	 *
	 * @param Request $request
	 * @param int $id
	 * @param \Closure $callback
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	private function handleMoveUpOrDownRequest(Request $request, int $id, \Closure $callback)
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClass());

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
			return $this->json([
				'success' => true
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
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	protected function doMoveToAction(Request $request)
	{
		$id = (int)$request->get('id') ?: null;

		$entity = $this->getDoctrine()->getRepository($this->getEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		if (!$entity instanceof SortInterface)
		{
			return $this->sortInterfaceNotImplemented($request, $id);
		}

		$parentId = (int)$request->get('parentId') ?: null;
		$siblingId = (int)$request->get('siblingId') ?: null;

		if ($entity instanceof TreeInterface)
		{
			if ($parentId && $siblingId)
			{
				return $this->handleMoveToParentAndSiblingRequest($request, $entity, $parentId, $siblingId);
			}
			else
			{
				if ($parentId)
				{
					return $this->handleMoveToParentRequest($request, $entity, $parentId);
				}
			}
		}

		return $this->handleMoveToSiblingRequest($request, $entity, $siblingId);
	}

	/**
	 * Move entity to
	 *
	 * @param Request $request
	 *
	 * @return RedirectResponse|JsonResponse
	 */
	abstract public function moveToAction(Request $request);

	/**
	 * Handle move to parent and sibling request
	 *
	 * @param Request $request
	 * @param SortInterface $entity
	 * @param int $parentId
	 * @param int $siblingId
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	private function handleMoveToParentAndSiblingRequest(Request $request, SortInterface $entity, int $parentId, int $siblingId)
	{
		$em = $this->getDoctrine()->getManager();

		/**
		 * @var EntityRepository|SortTrait $repository
		 */
		$repository = $em->getRepository($this->getEntityClass());

		/**
		 * @var TreeInterface $parent
		 */
		if (($parent = $repository->find($parentId)) === null)
		{
			return $this->createMoveToException($request, "Parent '{$this->getEntityClass()}::{$parentId}' not found");
		}

		/**
		 * @var SortInterface $sibling
		 */
		if (($sibling = $repository->find($siblingId)) === null)
		{
			return $this->createMoveToException($request, "Sibling '{$this->getEntityClass()}::{$siblingId}' not found");
		}

		/**
		 * @var TreeInterface $entity
		 */
		$previousParent = $entity->getParent();

		// check whether or not the parent changed
		if ($previousParent !== $parent)
		{
			$weight = 0;

			if ($previousParent !== null)
			{
				// remove entity from previous parent
				$previousParent->removeChild($entity);

				// update weight of children in previous parent
				foreach ($previousParent->getChildren() as $child)
				{
					/**
					 * @var SortInterface $child
					 */
					$child->setWeight($weight++);

					$em->persist($child);
				}

				$em->persist($previousParent);
			}
			else
			{
				// update weight of previous siblings
				foreach ($repository->findSiblingsToSort($entity) as $child)
				{
					// ignore if child is entity
					if ($child === $entity)
					{
						continue;
					}

					$child->setWeight($weight++);

					$em->persist($child);
				}
			}
		}

		// parent doesn't contain sibling
		if (!$parent->getChildren()->contains($sibling))
		{
			return $this->createMoveToException(
				$request,
				"Parent '{$this->getEntityClass()}::{$parentId}'doesn't contain sibling '{$this->getEntityClass()}::{$siblingId}'"
			);
		}

		$weight = 0;

		// update weight of children
		foreach ($parent->getChildren() as $child)
		{
			// ignore if child is entity
			if ($child === $entity)
			{
				continue;
			}

			$child->setWeight($weight++);

			$em->persist($child);

			// insert after sibling
			if ($child === $sibling)
			{
				$entity->setWeight($weight++);
			}
		}

		$parent->addChild($entity);

		$em->persist($parent);
		$em->persist($entity);
		$em->flush();

		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'success' => true
			]);
		}

		return $this->redirectToReferer(
			$this->generateUrl("{$this->getRoutePrefix()}_index"),
			$request
		);
	}

	/**
	 * Handle move to parent request
	 *
	 * @param Request $request
	 * @param SortInterface $entity
	 * @param int $parentId
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	private function handleMoveToParentRequest(Request $request, SortInterface $entity, int $parentId)
	{
		$em = $this->getDoctrine()->getManager();

		/**
		 * @var EntityRepository|SortTrait $repository
		 */
		$repository = $em->getRepository($this->getEntityClass());

		/**
		 * @var TreeInterface $parent
		 */
		if (($parent = $repository->find($parentId)) === null)
		{
			return $this->createMoveToException($request, "Parent '{$this->getEntityClass()}::{$parentId}' not found");
		}

		/**
		 * @var TreeInterface $entity
		 */
		$previousParent = $entity->getParent();

		// check whether or not the parent changed
		if ($previousParent !== $parent)
		{
			$weight = 0;

			if ($previousParent !== null)
			{
				// remove entity from previous parent
				$previousParent->removeChild($entity);

				// update weight of children in previous parent
				foreach ($previousParent->getChildren() as $child)
				{
					/**
					 * @var SortInterface $child
					 */
					$child->setWeight($weight++);

					$em->persist($child);
				}

				$em->persist($previousParent);
			}
			else
			{
				// update weight of previous siblings
				foreach ($repository->findSiblingsToSort($entity) as $child)
				{
					// ignore if child is entity
					if ($child === $entity)
					{
						continue;
					}

					$child->setWeight($weight++);

					$em->persist($child);
				}
			}
		}

		// use weight of last child
		if (($child = $parent->getChildren()->last()))
		{
			$entity->setWeight($child->getWeight() + 1);
		}
		// or default to first
		else
		{
			$entity->setWeight(0);
		}

		$parent->addChild($entity);

		$em->persist($parent);
		$em->flush();

		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'success' => true
			]);
		}

		return $this->redirectToReferer(
			$this->generateUrl("{$this->getRoutePrefix()}_index"),
			$request
		);
	}

	/**
	 * Handle move to sibling request
	 *
	 * @param Request $request
	 * @param SortInterface $entity
	 * @param int $siblingId
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	private function handleMoveToSiblingRequest(Request $request, SortInterface $entity, int $siblingId)
	{
		$em = $this->getDoctrine()->getManager();

		/**
		 * @var EntityRepository|SortTrait $repository
		 */
		$repository = $em->getRepository($this->getEntityClass());

		/**
		 * @var SortInterface $sibling
		 */
		if (($sibling = $repository->find($siblingId)) === null)
		{
			return $this->createMoveToException($request, "Sibling '{$this->getEntityClass()}::{$siblingId}' not found");
		}

		// check whether or not the parent changed
		if ($entity instanceof TreeInterface)
		{
			$previousParent = $entity->getParent();

			if ($previousParent !== null)
			{
				// remove entity from previous parent
				$previousParent->removeChild($entity);

				$weight = 0;

				// update weight of children in previous parent
				foreach ($previousParent->getChildren() as $child)
				{
					/**
					 * @var SortInterface $child
					 */
					$child->setWeight($weight++);

					$em->persist($child);
				}

				$em->persist($previousParent);
			}

			$entity->setParent(null);
		}

		$weight = 0;

		foreach ($repository->findSiblingsToSort($sibling) as $child)
		{
			// ignore if child is entity
			if ($child === $entity)
			{
				continue;
			}

			$child->setWeight($weight++);

			$em->persist($child);

			// insert after sibling
			if ($child === $sibling)
			{
				$entity->setWeight($weight++);
			}
		}

		$em->persist($entity);
		$em->flush();

		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'success' => true
			]);
		}

		return $this->redirectToReferer(
			$this->generateUrl("{$this->getRoutePrefix()}_index"),
			$request
		);
	}

	/**
	 * Create move to exception
	 *
	 * @param Request $request
	 * @param string $error
	 *
	 * @return JsonResponse
	 *
	 * @throws \Throwable
	 */
	private function createMoveToException(Request $request, string $error)
	{
		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'error' => $error
			]);
		}

		throw $this->createNotFoundException($error);
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

		$error = "Entity '{$this->getEntityClass()}::{$id}' doesn't implement '{$interface}'";

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'error' => $error
			]);
		}

		throw $this->createNotFoundException($error);
	}
}