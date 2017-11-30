<?php

namespace Softmedia\AdminBundle\Controller\Behavior;

use Softmedia\AdminBundle\Controller\AbstractAdminController;
use Softmedia\AdminBundle\Entity\Behavior;
use Softmedia\AdminBundle\Helper\ReflectionClassHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

trait SortableTrait
{
	/**
	 * Move entity up
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse
	 */
	protected function doMoveUp(Request $request, int $id): RedirectResponse
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

		if (ReflectionClassHelper::hasTrait($reflectionClass, Behavior\SortableTrait::class))
		{
			// TODO: implement move up
		}

		return $this->redirectToRoute("softmedia_admin_{$this->getRoutePrefix()}_list");
	}

	/**
	 * Move entity down
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse
	 */
	protected function doMoveDown(Request $request, int $id): RedirectResponse
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

		if (ReflectionClassHelper::hasTrait($reflectionClass, Behavior\SortableTrait::class))
		{
			// TODO: implement move down
		}

		return $this->redirectToRoute("softmedia_admin_{$this->getRoutePrefix()}_list");
	}

	/**
	 * Move entity up
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse
	 */
	abstract public function moveUp(Request $request, int $id): RedirectResponse;

	/**
	 * Move entity down
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse
	 */
	abstract public function moveDown(Request $request, int $id): RedirectResponse;
}