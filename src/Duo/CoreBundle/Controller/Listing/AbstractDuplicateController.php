<?php

namespace Duo\CoreBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractController;
use Duo\CoreBundle\Entity\DuplicateInterface;
use Duo\CoreBundle\Event\Listing\DuplicateEvent;
use Duo\CoreBundle\Event\Listing\DuplicateEvents;
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

		if (!$entity instanceof DuplicateInterface)
		{
			return $this->interfaceNotImplemented($request, $id, DuplicateInterface::class);
		}

		$clone = clone $entity;

		// dispatch event
		$this->get('event_dispatcher')->dispatch(DuplicateEvents::DUPLICATE, new DuplicateEvent($clone));

		$manager = $this->getDoctrine()->getManager();
		$manager->persist($clone);
		$manager->flush();

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'id' => $clone->getId(),
				'message' => $this->get('translator')->trans('duo_admin.duplicate_success', [], 'flashes')
			]);
		}

		$this->addFlash('success', $this->get('translator')->trans('duo_admin.duplicate_success', [], 'flashes'));

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
