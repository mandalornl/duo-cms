<?php

namespace Duo\BehaviorBundle\Controller;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\AdminBundle\Controller\AbstractController;
use Duo\BehaviorBundle\Entity\VersionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

trait CloneTrait
{
	/**
	 * Duplicate entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws AnnotationException
	 */
	protected function doDuplicateAction(Request $request, int $id)
	{
		/**
		 * @var AbstractController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityClassName())->find($id);
		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		$clone = clone $entity;

		// use clone as initial version
		if ($clone instanceof VersionInterface)
		{
			$clone->setVersion($clone);
		}

		/**
		 * @var ObjectManager $em
		 */
		$em = $this->getDoctrine()->getManager();
		$em->persist($clone);
		$em->flush();

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return new JsonResponse([
				'result' => [
					'success' => true,
					'id' => $clone->getId()
				]
			]);
		}

		return $this->redirectToRoute("{$this->getRoutePrefix()}_edit", [
			'id' => $clone->getId()
		]);
	}
}