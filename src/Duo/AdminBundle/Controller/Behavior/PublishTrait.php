<?php

namespace Duo\AdminBundle\Controller\Behavior;

use Doctrine\Common\Persistence\ObjectManager;
use Duo\AdminBundle\Controller\AbstractAdminController;
use Duo\AdminBundle\Entity\Behavior\PublishInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait PublishTrait
{
	/**
	 * Publish action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	protected function doPublishAction(Request $request, int $id)
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($id, $request);
		}

		if (!$entity instanceof PublishInterface)
		{
			return $this->publishableInterfaceNotImplemented($id, $request);
		}

		$entity->setPublished(true);

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
					'success' => true,
					'id' => $id
				]
			]);
		}

		return $this->redirectToRoute("duo_admin_{$this->getListType()}_edit", [
			'id' => $id
		]);
	}

	/**
	 * Unpublish action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	protected function doUnpublishAction(Request $request, int $id)
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($id, $request);
		}

		if (!$entity instanceof PublishInterface)
		{
			return $this->publishableInterfaceNotImplemented($id, $request);
		}

		$entity->setPublished(false);

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
					'success' => true,
					'id' => $id
				]
			]);
		}

		return $this->redirectToRoute("duo_admin_{$this->getListType()}_edit", [
			'id' => $id
		]);
	}

	/**
	 * Publishable interface not implemented
	 *
	 * @param int $id
	 * @param Request $request
	 *
	 * @return Response|JsonResponse
	 */
	private function publishableInterfaceNotImplemented(int $id, Request $request)
	{
		$interface = PublishInterface::class;
		$error = "Entity of type '{$this->getEntityClassName()}' with id '{$id}' doesn't implement '{$interface}'";

		if ($request->getMethod() === 'post')
		{
			return new JsonResponse([
				'result' => [
					'success' =>  false,
					'error' => $error
				]
			]);
		}
		return new Response($error, 500);
	}

	/**
	 * Publish action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	abstract public function publishAction(Request $request, int $id);

	/**
	 * Unpublish action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	abstract public function unpublishAction(Request $request, int $id);
}