<?php

namespace Duo\CoreBundle\Controller\Listing;

use Doctrine\ORM\EntityRepository;
use Duo\AdminBundle\Controller\Listing\AbstractController;
use Duo\CoreBundle\Entity\Property\SortInterface;
use Duo\CoreBundle\Entity\Property\TreeInterface;
use Duo\CoreBundle\Event\Listing\SortEvent;
use Duo\CoreBundle\Event\Listing\SortEvents;
use Duo\CoreBundle\Repository\SortInterface as RepositorySortInterface;
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
		return $this->handleMoveUpOrDownRequest(function(SortInterface $entity)
		{
			$manager = $this->getDoctrine()->getManager();

			/**
			 * @var RepositorySortInterface $repository
			 */
			$repository = $manager->getRepository($this->getEntityClass());

			$prevEntity = $repository->findPrevToSort($entity);

			if ($prevEntity !== null)
			{
				$weight = $prevEntity->getWeight();
				$prevEntity->setWeight($entity->getWeight());
				$entity->setWeight($weight);

				// dispatch sort event
				$this->get('event_dispatcher')->dispatch(SortEvents::SORT, new SortEvent($prevEntity));
				$this->get('event_dispatcher')->dispatch(SortEvents::SORT, new SortEvent($entity));

				$manager->persist($prevEntity);
				$manager->persist($entity);
				$manager->flush();
			}
		}, $request, $id);
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
		return $this->handleMoveUpOrDownRequest(function(SortInterface $entity)
		{
			$manager = $this->getDoctrine()->getManager();

			/**
			 * @var RepositorySortInterface $repository
			 */
			$repository = $manager->getRepository($this->getEntityClass());

			$nextEntity = $repository->findNextToSort($entity);

			if ($nextEntity !== null)
			{
				$weight = $nextEntity->getWeight();
				$nextEntity->setWeight($entity->getWeight());
				$entity->setWeight($weight);

				// dispatch sort event
				$this->get('event_dispatcher')->dispatch(SortEvents::SORT, new SortEvent($nextEntity));
				$this->get('event_dispatcher')->dispatch(SortEvents::SORT, new SortEvent($entity));

				$manager->persist($nextEntity);
				$manager->persist($entity);
				$manager->flush();
			}
		}, $request, $id);
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
	 * @param \Closure $callback
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	private function handleMoveUpOrDownRequest(\Closure $callback, Request $request, int $id): Response
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClass());

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		if (!$entity instanceof SortInterface)
		{
			return $this->interfaceNotImplemented($request, $id, SortInterface::class);
		}

		call_user_func_array($callback, [ $entity ]);

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'success' => true
			]);
		}

		return $this->redirectToReferer($request, $this->generateUrl("{$this->getRoutePrefix()}_index"));
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
		if (!($id = (int)$request->get('id')))
		{
			return $this->createMoveToException($request, 'Missing \'id\' parameter');
		}

		if (!($parentId = (int)$request->get('parentId')))
		{
			return $this->createMoveToException($request, 'Missing \'parentId\' parameter');
		}

		$entity = $this->getDoctrine()->getRepository($this->getEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		if (!$entity instanceof SortInterface)
		{
			return $this->interfaceNotImplemented($request, $id, SortInterface::class);
		}

		$manager = $this->getDoctrine()->getManager();

		/**
		 * @var EntityRepository|RepositorySortInterface $repository
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

							// dispatch sort event
							$this->get('event_dispatcher')->dispatch(SortEvents::SORT, new SortEvent($child));

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

							// dispatch sort event
							$this->get('event_dispatcher')->dispatch(SortEvents::SORT, new SortEvent($child));

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

						// dispatch sort event
						$this->get('event_dispatcher')->dispatch(SortEvents::SORT, new SortEvent($child));

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
		if (($prevSiblingId = (int)$request->get('prevSiblingId')) &&
			($prevSibling = $repository->find($prevSiblingId)) !== null)
		{
			$siblings = $repository->findPrevAllToSort($prevSibling);

			// ignore if entity is sibling
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
		if (($nextSiblingId = (int)$request->get('nextSiblingId')) &&
			($nextSibling = $repository->find($nextSiblingId)) !== null)
		{
			$siblings = $repository->findNextAllToSort($nextSibling);

			// ignore if entity is sibling
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

			// dispatch sort event
			$this->get('event_dispatcher')->dispatch(SortEvents::SORT, new SortEvent($child));

			$manager->persist($child);
		}

		$manager->flush();

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'success' => true
			]);
		}

		return $this->redirectToReferer($request, $this->generateUrl("{$this->getRoutePrefix()}_index"));
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
				'error' => $error,
				'message' => $this->get('translator')->trans('duo_admin.error', [], 'flashes')
			]);
		}

		throw $this->createNotFoundException($error);
	}
}
