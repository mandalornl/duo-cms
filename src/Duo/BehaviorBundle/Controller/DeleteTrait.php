<?php

namespace Duo\BehaviorBundle\Controller;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\AdminBundle\Controller\Listing\AbstractController;
use Duo\BehaviorBundle\Entity\DeleteInterface;
use Duo\BehaviorBundle\Event\DeleteEvent;
use Duo\BehaviorBundle\Event\DeleteEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

trait DeleteTrait
{
	/**
	 * Delete entity
	 *
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws AnnotationException
	 */
	protected function doDeleteAction(Request $request, int $id = null)
	{
		if ($id === null)
		{
			return $this->handleMultiDeletionRequest($request, function(DeleteInterface $entity)
			{
				$entity->delete();
			}, DeleteEvents::DELETE, 'duo.admin.listing.alert.delete_success');
		}

		return $this->handleDeletionRequest($request, $id, function(DeleteInterface $entity)
		{
			$entity->delete();
		}, DeleteEvents::DELETE, 'duo.admin.listing.alert.delete_success');
	}

	/**
	 * Undelete entity
	 *
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws AnnotationException
	 */
	protected function doUndeleteAction(Request $request, int $id = null)
	{
		if ($id === null)
		{
			return $this->handleMultiDeletionRequest($request, function(DeleteInterface $entity)
			{
				$entity->undelete();
			}, DeleteEvents::UNDELETE, 'duo.admin.listing.alert.undelete_success');
		}

		return $this->handleDeletionRequest($request, $id, function(DeleteInterface $entity)
		{
			$entity->undelete();
		}, DeleteEvents::UNDELETE, 'duo.admin.listing.alert.undelete_success');
	}

	/**
	 * Handle deletion request
	 *
	 * @param Request $request
	 * @param int $id
	 * @param \Closure $callback
	 * @param string $eventName
	 * @param string $message
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws AnnotationException
	 */
	private function handleDeletionRequest(Request $request, int $id, \Closure $callback, string $eventName, string $message)
	{
		/**
		 * @var AbstractController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		if (!$entity instanceof DeleteInterface)
		{
			return $this->deleteInterfaceNotImplemented($request, $id);
		}

		call_user_func($callback, $entity);

		/**
		 * @var EventDispatcherInterface $dispatcher
		 */
		$dispatcher = $this->get('event_dispatcher');
		$dispatcher->dispatch($eventName, new DeleteEvent($entity));

		/**
		 * @var ObjectManager $em
		 */
		$em = $this->getDoctrine()->getManager();
		$em->persist($entity);
		$em->flush();

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return new JsonResponse([
				'result' => [
					'success' => true,
					'message' => $this->get('translator')->trans($message)
				]
			]);
		}

		$this->addFlash('success', $this->get('translator')->trans($message));

		return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
	}

	/**
	 * Handle multi deletion request
	 *
	 * @param Request $request
	 * @param \Closure $callback
	 * @param string $eventName
	 * @param string $message
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws AnnotationException
	 */
	private function handleMultiDeletionRequest(Request $request, \Closure $callback, string $eventName, string $message)
	{
		if (!count($ids = $request->get('ids')))
		{
			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return new JsonResponse([
					'result' => [
						'success' => false,
						'message' => $this->get('translator')->trans('duo.admin.listing.alert.no_items')
					]
				]);
			}

			$this->addFlash('warning', $this->get('translator')->trans('duo.admin.listing.alert.no_items'));
		}
		else
		{
			/**
			 * @var ObjectManager $em
			 */
			$em = $this->getDoctrine()->getManager();

			/**
			 * @var EventDispatcherInterface $dispatcher
			 */
			$dispatcher = $this->get('event_dispatcher');

			$entities = $this->getDoctrine()->getRepository($this->getEntityClassName())->findBy([
				'id' => $ids
			]);

			foreach ($entities as $entity)
			{
				call_user_func($callback, $entity);

				$dispatcher->dispatch($eventName, new DeleteEvent($entity));

				$em->persist($entity);
			}

			$em->flush();

			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return new JsonResponse([
					'result' => [
						'success' => true,
						'message' => $this->get('translator')->trans($message)
					]
				]);
			}

			$this->addFlash('success', $this->get('translator')->trans($message));
		}

		return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
	}

	/**
	 * Soft deletable interface not implemented
	 *
	 * @param int $id
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	private function deleteInterfaceNotImplemented(Request $request, int $id): JsonResponse
	{
		$interface = DeleteInterface::class;
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