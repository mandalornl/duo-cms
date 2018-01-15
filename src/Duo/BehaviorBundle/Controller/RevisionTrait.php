<?php

namespace Duo\BehaviorBundle\Controller;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\AdminBundle\Controller\Listing\AbstractController;
use Duo\BehaviorBundle\Entity\RevisionInterface;
use Duo\BehaviorBundle\Event\RevisionEvent;
use Duo\BehaviorBundle\Event\RevisionEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait RevisionTrait
{
	/**
	 * Revision action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|JsonResponse
	 *
	 * @throws AnnotationException
	 */
	protected function doRevisionAction(Request $request, int $id)
	{
		/**
		 * @var AbstractController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		if (!$entity instanceof RevisionInterface)
		{
			return $this->revisionInterfaceNotImplemented($request, $id);
		}

		/**
		 * @var FormInterface $form
		 */
		$form = $this->createForm($this->getFormClassName(), $entity, [
			'action' => 'javascript:;',
			'disabled' => true
		]);

		return $this->render($this->getRevisionTemplate(), [
			'menu' => $this->get('duo.admin.menu_builder')->createView(),
			'entity' => $entity,
			'form' => $form->createView(),
			'type' => $this->getType(),
			'routePrefix' => $this->getRoutePrefix()
		]);
	}

	/**
	 * Revert action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws AnnotationException
	 */
	protected function doRevertAction(Request $request, int $id)
	{
		/**
		 * @var AbstractController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		if (!$entity instanceof RevisionInterface)
		{
			return $this->revisionInterfaceNotImplemented($request, $id);
		}

		/**
		 * @var EventDispatcherInterface $dispatcher
		 */
		$dispatcher = $this->get('event_dispatcher');
		$dispatcher->dispatch(RevisionEvents::REVERT, new RevisionEvent($entity, $entity->getRevision()));

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
					'id' => $entity->getId(),
					'message' => $this->get('translator')->trans('duo.behavior.listing.alert.revert_success')
				]
			]);
		}

		$this->addFlash('success', $this->get('translator')->trans('duo.behavior.listing.alert.revert_success'));

		return $this->redirectToRoute("{$this->getRoutePrefix()}_edit", [
			'id' => $entity->getId()
		]);
	}

	/**
	 * Revision interface not implemented
	 *
	 * @param int $id
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	private function revisionInterfaceNotImplemented(Request $request, int $id): JsonResponse
	{
		$interface = RevisionInterface::class;
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

	/**
	 * Get revision template
	 *
	 * @return string
	 */
	protected function getRevisionTemplate(): string
	{
		return '@DuoAdmin/Listing/revision.html.twig';
	}
}