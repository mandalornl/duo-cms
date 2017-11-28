<?php

namespace Softmedia\AdminBundle\Controller;

use Softmedia\AdminBundle\Entity\Behavior;
use Softmedia\AdminBundle\Helper\ReflectionClassHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

trait SoftDeletableTrait
{
	/**
	 * Delete entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse
	 */
	protected function doDeleteIndex(Request $request, int $id): RedirectResponse
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			// TODO: implement exception
		}

		$reflectionClass = new \ReflectionClass($entity);

		if (ReflectionClassHelper::hasTrait($reflectionClass, SoftDeletableTrait::class))
		{
			/**
			 * @var Behavior\SoftDeletableTrait $entity
			 */
			$entity->delete();

			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();
		}

		return $this->redirectToRoute("softmedia_admin_{$this->getRoutePrefix()}_list");
	}

	/**
	 * Restore entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse
	 */
	protected function doRestoreAction(Request $request, int $id): RedirectResponse
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			// TODO: implement exception
		}

		$reflectionClass = new \ReflectionClass($entity);

		if (ReflectionClassHelper::hasTrait($reflectionClass, Behavior\SoftDeletableTrait::class))
		{
			/**
			 * @var Behavior\SoftDeletableTrait $entity
			 */
			$entity->restore();

			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();
		}

		return $this->redirect("softmedia_admin_{$this->getRoutePrefix()}_list");
	}

	/**
	 * Delete entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse
	 */
	abstract public function deleteIndex(Request $request, int $id): RedirectResponse;

	/**
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse
	 */
	abstract public function restoreIndex(Request $request, int $id): RedirectResponse;
}