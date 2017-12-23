<?php

namespace Duo\AdminBundle\Controller\Behavior;

use Doctrine\Common\Persistence\ObjectManager;
use Duo\AdminBundle\Controller\AbstractAdminController;
use Duo\AdminBundle\Entity\Behavior\VersionInterface;
use Duo\AdminBundle\Event\Behavior\VersionEvent;
use Duo\AdminBundle\Event\Behavior\VersionEvents;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
		 * @var AbstractAdminController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($id, $request);
		}

		if (!$entity instanceof VersionInterface)
		{
			return $this->versionableInterfaceNotImplemented($id, $request);
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
	 * @return Response|JsonResponse
	 */
	protected function doRevertAction(Request $request, int $id)
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($id, $request);
		}

		if (!$entity instanceof VersionInterface)
		{
			return $this->versionableInterfaceNotImplemented($id, $request);
		}

		/**
		 * @var TraceableEventDispatcher $dispatcher
		 */
		$dispatcher = $this->get('event_dispatcher');
		$dispatcher->dispatch(VersionEvents::PRE_REVERT, new VersionEvent($entity->getVersion(), $entity));

		/**
		 * @var ObjectManager $em
		 */
		$em = $this->getDoctrine()->getManager();
		$em->persist($entity);
		$em->flush();

		$dispatcher->dispatch(VersionEvents::POST_REVERT, new VersionEvent($entity->getVersion(), $entity));

		if ($request->getMethod() === 'post')
		{
			return new JsonResponse([
				'result' => [
					'success' => true,
					'id' => $entity->getId()
				]
			]);
		}

		return $this->redirectToRoute("duo_admin_page_edit", [
			'id' => $entity->getId()
		]);
	}

	/**
	 * Versionable interface not implemented
	 *
	 * @param int $id
	 * @param Request $request
	 *
	 * @return Response|JsonResponse
	 */
	private function versionableInterfaceNotImplemented(int $id, Request $request)
	{
		$interface = VersionInterface::class;
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

	/**
	 * Get version template
	 *
	 * @return string
	 */
	protected function getVersionTemplate(): string
	{
		return '@DuoAdmin/List/version.html.twig';
	}

	/**
	 * Version action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|JsonResponse
	 */
	abstract public function versionAction(Request $request, int $id);

	/**
	 * Revert action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|JsonResponse
	 */
	abstract public function revertAction(Request $request, int $id);
}