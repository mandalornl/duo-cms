<?php

namespace Duo\AdminBundle\Controller;

use Duo\CoreBundle\Entity\Property\DeleteInterface;
use Duo\CoreBundle\Entity\Property\IdInterface;
use Duo\CoreBundle\Event\DeleteEvent;
use Duo\CoreBundle\Event\DeleteEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractDeleteController extends AbstractController
{
	/**
	 * Delete entity
	 *
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	protected function doDeleteAction(Request $request, int $id = null): Response
	{
		return $this->handleDeletionRequest(function(DeleteInterface $entity, EventDispatcherInterface $dispatcher)
		{
			$entity->delete();

			$dispatcher->dispatch(DeleteEvents::DELETE, new DeleteEvent($entity));
		}, 'duo.admin.listing.alert.delete_success', $request, $id);
	}

	/**
	 * Delete entity
	 *
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	abstract public function deleteAction(Request $request, int $id = null): Response;

	/**
	 * Undelete entity
	 *
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	protected function doUndeleteAction(Request $request, int $id = null): Response
	{
		return $this->handleDeletionRequest(function(DeleteInterface $entity, EventDispatcherInterface $dispatcher)
		{
			$entity->undelete();

			$dispatcher->dispatch(DeleteEvents::UNDELETE, new DeleteEvent($entity));
		}, 'duo.admin.listing.alert.undelete_success', $request, $id);
	}

	/**
	 * Undelete entity
	 *
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	abstract public function undeleteAction(Request $request, int $id = null): Response;

	/**
	 * Handle deletion request
	 *
	 * @param \Closure $callback
	 * @param string $message
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	private function handleDeletionRequest(\Closure $callback, string $message, Request $request, int $id = null): Response
	{
		$selection = (array)$id ?: $request->get('ids', []);

		if (!count($selection))
		{
			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return $this->json([
					'success' => false,
					'message' => $this->get('translator')->trans('duo.admin.listing.alert.no_items')
				]);
			}

			$this->addFlash('warning', $this->get('translator')->trans('duo.admin.listing.alert.no_items'));
		}
		else
		{
			$manager = $this->getDoctrine()->getManager();

			$dispatcher = $this->get('event_dispatcher');

			foreach (array_chunk($selection, 100) as $ids)
			{
				$entities = $this->getDoctrine()->getRepository($this->getEntityClass())->findBy([
					'id' => $ids
				]);

				foreach ($entities as $entity)
				{
					/**
					 * @var IdInterface|DeleteInterface $entity
					 */
					if (!$entity instanceof DeleteInterface)
					{
						return $this->interfaceNotImplemented($request, $entity->getId(), DeleteInterface::class);
					}

					call_user_func_array($callback, [ $entity, $dispatcher ]);

					$manager->persist($entity);
				}

				$manager->flush();
				$manager->clear();
			}

			$manager->flush();

			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return $this->json([
					'success' => true,
					'message' => $this->get('translator')->trans($message)
				]);
			}

			$this->addFlash('success', $this->get('translator')->trans($message));
		}

		return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
	}
}