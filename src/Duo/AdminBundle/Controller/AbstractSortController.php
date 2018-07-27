<?php

namespace Duo\AdminBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Duo\CoreBundle\Entity\SortInterface;
use Duo\CoreBundle\Entity\TreeInterface;
use Duo\CoreBundle\Event\SortEvent;
use Duo\CoreBundle\Event\SortEvents;
use Duo\CoreBundle\Repository\SortTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
	protected function doMoveUpAction(Request $request, int $id): Response
	{
		return $this->handleMoveUpOrDownRequest($request, $id, function(SortInterface $entity)
		{
			$manager = $this->getDoctrine()->getManager();

			/**
			 * @var SortTrait $repository
			 */
			$repository = $manager->getRepository($this->getEntityClass());

			$previousEntity = $repository->findPrevToSort($entity);

			if ($previousEntity !== null)
			{
				$weight = $previousEntity->getWeight();
				$previousEntity->setWeight($entity->getWeight());
				$entity->setWeight($weight);

				// dispatch sort event
				$this->get('event_dispatcher')->dispatch(SortEvents::SORT, new SortEvent($entity, $previousEntity));

				$manager->persist($previousEntity);
				$manager->persist($entity);
				$manager->flush();
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
	 *
	 * @throws \Throwable
	 */
	abstract public function moveUpAction(Request $request, int $id): Response;

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
	protected function doMoveDownAction(Request $request, int $id): Response
	{
		return $this->handleMoveUpOrDownRequest($request, $id, function(SortInterface $entity)
		{
			$manager = $this->getDoctrine()->getManager();

			/**
			 * @var SortTrait $repository
			 */
			$repository = $manager->getRepository($this->getEntityClass());

			$nextEntity = $repository->findNextToSort($entity);

			if ($nextEntity !== null)
			{
				$weight = $nextEntity->getWeight();
				$nextEntity->setWeight($entity->getWeight());
				$entity->setWeight($weight);

				// dispatch sort event
				$this->get('event_dispatcher')->dispatch(SortEvents::SORT, new SortEvent($entity, $nextEntity));

				$manager->persist($nextEntity);
				$manager->persist($entity);
				$manager->flush();
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
	 * @throws \Throwable
	 */
	abstract public function moveDownAction(Request $request, int $id): Response;

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
	private function handleMoveUpOrDownRequest(Request $request, int $id, \Closure $callback): Response
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

		call_user_func_array($callback, [ $entity ]);

		// reply with json response
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
	protected function doMoveToAction(Request $request): Response
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
		$prevSiblingId = (int)$request->get('prevSiblingId') ?: null;
		$nextSiblingId = (int)$request->get('nextSiblingId') ?: null;

		if (!$parentId && !$prevSiblingId && !$nextSiblingId)
		{
			return $this->createMoveToException($request, 'Missing parent and/or prev/next sibling id\'s');
		}

		$manager = $this->getDoctrine()->getManager();

		/**
		 * @var EntityRepository|SortTrait $repository
		 */
		$repository = $manager->getRepository($this->getEntityClass());

		if ($entity instanceof TreeInterface)
		{
			$prevParent = $entity->getParent();

			/**
			 * @var TreeInterface $parent
			 */
			$parent = $parentId ? $repository->find($parentId) : null;

			// update parent
			if ($parent !== null)
			{
				// check whether or not the parent changed
				if ($prevParent !== $parent)
				{
					$weight = 0;

					// update previous parent
					if ($prevParent !== null)
					{
						// remove entity from previous parent
						$prevParent->removeChild($entity);

						// update weight of children in previous parent
						foreach ($prevParent->getChildren() as $child)
						{
							/**
							 * @var SortInterface $child
							 */
							$child->setWeight($weight++);

							$manager->persist($child);
						}

						$manager->persist($prevParent);
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

							$manager->persist($child);
						}
					}
				}

				$parent->addChild($entity);

				$manager->persist($parent);
			}
			else
			{
				// update previous parent
				if ($prevParent !== null)
				{
					$weight = 0;

					// remove entity from previous parent
					$prevParent->removeChild($entity);

					// update weight of children in previous parent
					foreach ($prevParent->getChildren() as $child)
					{
						/**
						 * @var SortInterface $child
						 */
						$child->setWeight($weight++);

						$manager->persist($child);
					}

					$manager->persist($prevParent);
				}

				$entity->setParent(null);
			}
		}

		$children = [];

		/**
		 * @var SortInterface $prevSibling
		 */
		if ($prevSiblingId && ($prevSibling = $repository->find($prevSiblingId)) !== null)
		{
			$siblings = $repository->findPrevAllToSort($prevSibling);

			if (($index = array_search($entity, $siblings)) !== false)
			{
				unset($siblings[$index]);
			}

			$children = array_merge($children, $siblings);
			$children[] = $prevSibling;
		}

		$children[] = $entity;

		/**
		 * @var SortInterface $nextSibling
		 */
		if ($nextSiblingId && ($nextSibling = $repository->find($nextSiblingId)) !== null)
		{
			$siblings = $repository->findNextAllToSort($nextSibling);

			if (($index = array_search($entity, $siblings)) !== false)
			{
				unset($siblings[$index]);
			}

			$children[] = $nextSibling;
			$children = array_merge($children, $siblings);
		}

		$weight = 0;

		// update weight of children
		foreach ($children as $child)
		{
			$child->setWeight($weight++);

			$manager->persist($child);
		}

		$manager->flush();

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
	abstract public function moveToAction(Request $request): Response;

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
	private function createMoveToException(Request $request, string $error): JsonResponse
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