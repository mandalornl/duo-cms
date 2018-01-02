<?php

namespace Duo\AdminBundle\Controller\Behavior;

use Doctrine\Common\Persistence\ObjectManager;
use Duo\AdminBundle\Controller\Listing\AbstractController;
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

		return $this->redirectToRoute("duo_admin_listing_{$this->getListType()}_edit", [
			'id' => $clone->getId()
		]);
	}
}