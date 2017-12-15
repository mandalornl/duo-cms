<?php

namespace Softmedia\AdminBundle\Controller\Behavior;

use Softmedia\AdminBundle\Controller\AbstractAdminController;
use Softmedia\AdminBundle\Entity\Behavior\SoftDeletableInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait SoftDeletableTrait
{
	/**
	 * Delete entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	protected function doDeleteIndex(Request $request, int $id)
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($id);
		}

		if ($entity instanceof SoftDeletableInterface)
		{
			$entity->delete();

			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();
		}

		return $this->redirectToRoute("softmedia_admin_{$this->getListType()}_list");
	}

	/**
	 * Restore entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	protected function doRestoreAction(Request $request, int $id)
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($id);
		}

		if ($entity instanceof SoftDeletableInterface)
		{
			$entity->restore();

			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();
		}

		return $this->redirect("softmedia_admin_{$this->getListType()}_list");
	}

	/**
	 * Delete entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	abstract public function deleteIndex(Request $request, int $id);

	/**
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	abstract public function restoreIndex(Request $request, int $id);
}