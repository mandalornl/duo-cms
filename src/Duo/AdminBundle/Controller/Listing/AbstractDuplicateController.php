<?php

namespace Duo\AdminBundle\Controller\Listing;

use Duo\CoreBundle\Entity\DuplicateInterface;
use Duo\CoreBundle\Event\DuplicateEvent;
use Duo\CoreBundle\Event\DuplicateEvents;
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
				'success' => true,
				'id' => $clone->getId(),
				'message' => $this->get('translator')->trans('duo.admin.listing.alert.duplicate_success')
			]);
		}

		$this->addFlash('success', $this->get('translator')->trans('duo.admin.listing.alert.duplicate_success'));

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