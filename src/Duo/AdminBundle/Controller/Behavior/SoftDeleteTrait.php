<?php

namespace Duo\AdminBundle\Controller\Behavior;

use Doctrine\Common\Persistence\ObjectManager;
use Duo\AdminBundle\Controller\Listing\AbstractController;
use Duo\BehaviorBundle\Entity\SoftDeleteInterface;
use Duo\BehaviorBundle\Event\SoftDeleteEvent;
use Duo\BehaviorBundle\Event\SoftDeleteEvents;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait SoftDeleteTrait
{
	/**
	 * Delete entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	protected function doDeleteAction(Request $request, int $id)
	{
		/**
		 * @var AbstractController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($id, $request);
		}

		if (!$entity instanceof SoftDeleteInterface)
		{
			return $this->softDeletableInterfaceNotImplemented($id, $request);
		}

		$entity->delete();

		/**
		 * @var TraceableEventDispatcher $dispatcher
		 */
		$dispatcher = $this->get('event_dispatcher');
		$dispatcher->dispatch(SoftDeleteEvents::DELETE, new SoftDeleteEvent($entity));

		/**
		 * @var ObjectManager $em
		 */
		$em = $this->getDoctrine()->getManager();
		$em->persist($entity);
		$em->flush();

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
	 * Undelete entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	protected function doUndeleteAction(Request $request, int $id)
	{
		/**
		 * @var AbstractController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($id, $request);
		}

		if (!$entity instanceof SoftDeleteInterface)
		{
			return $this->softDeletableInterfaceNotImplemented($id, $request);
		}

		$entity->undelete();

		/**
		 * @var TraceableEventDispatcher $dispatcher
		 */
		$dispatcher = $this->get('event_dispatcher');
		$dispatcher->dispatch(SoftDeleteEvents::UNDELETE, new SoftDeleteEvent($entity));

		/**
		 * @var ObjectManager $em
		 */
		$em = $this->getDoctrine()->getManager();
		$em->persist($entity);
		$em->flush();

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
	 * Soft deletable interface not implemented
	 *
	 * @param int $id
	 * @param Request $request
	 *
	 * @return Response|JsonResponse
	 */
	private function softDeletableInterfaceNotImplemented(int $id, Request $request)
	{
		$interface = SoftDeleteInterface::class;
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
}