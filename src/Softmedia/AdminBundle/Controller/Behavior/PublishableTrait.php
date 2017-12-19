<?php

namespace Softmedia\AdminBundle\Controller\Behavior;

use Doctrine\Common\Persistence\ObjectManager;
use Softmedia\AdminBundle\Controller\AbstractAdminController;
use Softmedia\AdminBundle\Entity\Behavior\PublishableInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait PublishableTrait
{
	/**
	 * Publish action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	protected function doPublishAction(Request $request, int $id)
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($id);
		}

		if (!$entity instanceof PublishableInterface)
		{
			return $this->publishableInterfaceNotImplemented($id);
		}

		$entity->setPublished(true);

		/**
		 * @var ObjectManager $em
		 */
		$em = $this->getDoctrine()->getManager();
		$em->persist($entity);
		$em->flush();

		return $this->redirectToRoute("softmedia_admin_{$this->getListType()}_edit", [
			'id' => $id
		]);
	}

	/**
	 * Unpublish action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	protected function doUnpublishAction(Request $request, int $id)
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($id);
		}

		if (!$entity instanceof PublishableInterface)
		{
			return $this->publishableInterfaceNotImplemented($id);
		}

		$entity->setPublished(false);

		/**
		 * @var ObjectManager $em
		 */
		$em = $this->getDoctrine()->getManager();
		$em->persist($entity);
		$em->flush();

		return $this->redirectToRoute("softmedia_admin_{$this->getListType()}_edit", [
			'id' => $id
		]);
	}

	/**
	 * Publishable interface not implemented
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	private function publishableInterfaceNotImplemented(int $id): Response
	{
		$interface = PublishableInterface::class;
		return new Response("Entity of type '{$this->getEntityClassName()}' with id '{$id}' doesn't implement '{$interface}'", 500);
	}

	/**
	 * Publish action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	abstract public function publishAction(Request $request, int $id);

	/**
	 * Unpublish action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	abstract public function unpublishAction(Request $request, int $id);
}