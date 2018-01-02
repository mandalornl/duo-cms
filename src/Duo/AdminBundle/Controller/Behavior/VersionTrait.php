<?php

namespace Duo\AdminBundle\Controller\Behavior;

use Doctrine\Common\Persistence\ObjectManager;
use Duo\AdminBundle\Controller\Listing\AbstractController;
use Duo\BehaviorBundle\Entity\VersionInterface;
use Duo\BehaviorBundle\Event\VersionEvent;
use Duo\BehaviorBundle\Event\VersionEvents;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait VersionTrait
{
	/**
	 * Version action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|JsonResponse
	 */
	protected function doVersionAction(Request $request, int $id)
	{
		/**
		 * @var AbstractController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		if (!$entity instanceof VersionInterface)
		{
			return $this->versionInterfaceNotImplemented($request, $id);
		}

		/**
		 * @var FormInterface $form
		 */
		$form = $this->createForm($this->getFormClassName(), $entity, [
			'action' => 'javascript:;'
		]);

		return $this->render($this->getVersionTemplate(), [
			'entity' => $entity,
			'form' => $form->createView()
		]);
	}

	/**
	 * Revert action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
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

		if (!$entity instanceof VersionInterface)
		{
			return $this->versionInterfaceNotImplemented($request, $id);
		}

		/**
		 * @var TraceableEventDispatcher $dispatcher
		 */
		$dispatcher = $this->get('event_dispatcher');
		$dispatcher->dispatch(VersionEvents::REVERT, new VersionEvent($entity, $entity->getVersion()));

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
					'id' => $entity->getId()
				]
			]);
		}

		return $this->redirectToRoute("duo_admin_listing_{$this->getListType()}_edit", [
			'id' => $entity->getId()
		]);
	}

	/**
	 * Versionable interface not implemented
	 *
	 * @param int $id
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	private function versionInterfaceNotImplemented(Request $request, int $id): JsonResponse
	{
		$interface = VersionInterface::class;
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
	 * Get version template
	 *
	 * @return string
	 */
	protected function getVersionTemplate(): string
	{
		return '@DuoAdmin/Listing/version.html.twig';
	}
}