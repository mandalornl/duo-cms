<?php

namespace Duo\AdminBundle\Controller;

use Duo\CoreBundle\Entity\RevisionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractDuplicateController extends AbstractController
{
	/**
	 * Duplicate entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	protected function doDuplicateAction(Request $request, int $id): Response
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		$clone = clone $entity;

		// use clone as initial revision
		if ($clone instanceof RevisionInterface)
		{
			$clone->setRevision($clone);
		}

		$em = $this->getDoctrine()->getManager();
		$em->persist($clone);
		$em->flush();

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'success' => true,
				'id' => $clone->getId()
			]);
		}

		return $this->redirectToRoute("{$this->getRoutePrefix()}_update", [
			'id' => $clone->getId()
		]);
	}

	/**
	 * Duplicate action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	abstract public function duplicateAction(Request $request, int $id): Response;
}