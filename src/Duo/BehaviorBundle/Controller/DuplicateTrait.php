<?php

namespace Duo\BehaviorBundle\Controller;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Persistence\ObjectManager;
use Duo\AdminBundle\Controller\AbstractListingController;
use Duo\BehaviorBundle\Entity\RevisionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

trait DuplicateTrait
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
		 * @var AbstractListingController $this
		 */
		$entity = $this->getDoctrine()->getRepository($this->getEntityFqcn())->find($id);
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

	/**
	 * Duplicate action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 */
	abstract public function duplicateAction(Request $request, int $id);
}