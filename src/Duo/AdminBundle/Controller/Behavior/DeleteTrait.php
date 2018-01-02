<?php

namespace Duo\AdminBundle\Controller\Behavior;

use Doctrine\Common\Persistence\ObjectManager;
use Duo\AdminBundle\Controller\Listing\AbstractController;
use Duo\BehaviorBundle\Entity\DeleteInterface;
use Duo\BehaviorBundle\Event\DeleteEvent;
use Duo\BehaviorBundle\Event\DeleteEvents;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

trait DeleteTrait
{
	/**
	 * Delete entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 */
	protected function doDeleteAction(Request $request, int $id)
	{
		return $this->handleDeletionRequest($request, $id, function(DeleteInterface $entity)
		{
			$entity->delete();
		}, DeleteEvents::DELETE);
	}

	/**
	 * Undelete entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 */
	protected function doUndeleteAction(Request $request, int $id)
	{
		return $this->handleDeletionRequest($request, $id, function(DeleteInterface $entity)
		{
			$entity->undelete();
		}, DeleteEvents::UNDELETE);
	}

	/**
	 * Handle deletion request
	 *
	 * @param Request $request
	 * @param int $id
	 * @param \Closure $callback
	 * @param string $eventName
	 *
	 * @return JsonResponse
	 */
	private function handleDeletionRequest(Request $request, int $id, \Closure $callback, string $eventName)
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
		 * @var TraceableEventDispatcher $dispatcher
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
					'success' => true
				]
			]);
		}

		return $this->redirectToRoute("duo_admin_listing_{$this->getListType()}_index");
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