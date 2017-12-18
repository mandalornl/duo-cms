<?php

namespace Softmedia\AdminBundle\Controller\Behavior;

use Softmedia\AdminBundle\Controller\AbstractAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait CloneableTrait
{
	/**
	 * Version index
	 *
	 * @param Request $request
	 * @param int $id
	 * @param int $versionId
	 *
	 * @return Response
	 */
	protected function doVersionIndex(Request $request, int $id, int $versionId)
	{
		/**
		 * @var AbstractAdminController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($versionId);
		if ($entity === null)
		{
			return $this->versionNotFound($versionId);
		}

		// TODO: use form with disabled fields instead of plain entity?
		return $this->render($this->getVersionTemplate(), [
			'entity' => $entity
		]);
	}

	/**
	 * Get version template
	 *
	 * @return string
	 */
	protected function getVersionTemplate(): string
	{
		return '@SoftmediaAdmin/List/version.html.twig';
	}

	/**
	 * Version not found
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	protected function versionNotFound(int $id)
	{
		return new Response("Version for entity of type '{$this->getEntityClassName()}' with id '{$id}' not found", 404);
	}

	/**
	 * Version index
	 *
	 * @param Request $request
	 * @param int $id
	 * @param int $versionId
	 *
	 * @return Response
	 */
	abstract public function versionIndex(Request $request, int $id, int $versionId);
}