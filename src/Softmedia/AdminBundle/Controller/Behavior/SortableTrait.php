<?php

namespace Softmedia\AdminBundle\Controller\Behavior;

use Softmedia\AdminBundle\Controller\AbstractAdminController;
use Softmedia\AdminBundle\Entity\Behavior\SortableInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait SortableTrait
{
	/**
	 * Move entity up
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	protected function doMoveUp(Request $request, int $id)
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($id);
		}

		if (!$entity instanceof SortableInterface)
		{
			// TODO: implement move up
		}

		return $this->redirectToRoute("softmedia_admin_{$this->getListType()}_list");
	}

	/**
	 * Move entity down
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	protected function doMoveDown(Request $request, int $id)
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($id);
		}

		if (!$entity instanceof SortableInterface)
		{
			// TODO: implement move down
		}

		return $this->redirectToRoute("softmedia_admin_{$this->getListType()}_list");
	}

	/**
	 * Move entity up
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	abstract public function moveUp(Request $request, int $id);

	/**
	 * Move entity down
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	abstract public function moveDown(Request $request, int $id);
}