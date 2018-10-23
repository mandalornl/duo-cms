<?php

namespace Duo\CoreBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractController;
use Duo\CoreBundle\Entity\Property\RevisionInterface;
use Duo\CoreBundle\Event\Listing\RevisionEvent;
use Duo\CoreBundle\Event\Listing\RevisionEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractRevisionController extends AbstractController
{
	/**
	 * View action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|JsonResponse
	 *
	 * @throws \Throwable
	 */
	protected function doViewAction(Request $request, int $id): Response
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		if (!$entity instanceof RevisionInterface)
		{
			return $this->interfaceNotImplemented($request, $id, RevisionInterface::class);
		}

		$form = $this->createForm($this->getFormType(), $entity, [
			'action' => 'javascript:;',
			'disabled' => true
		]);

		return $this->render($this->getRevisionTemplate(), (array)$this->getDefaultContext([
			'entity' => $entity,
			'form' => $form->createView()
		]));
	}

	/**
	 * View action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|JsonResponse
	 *
	 * @throws \Throwable
	 */
	abstract public function viewAction(Request $request, int $id): Response;

	/**
	 * Revert action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	protected function doRevertAction(Request $request, int $id): Response
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		if (!$entity instanceof RevisionInterface)
		{
			return $this->interfaceNotImplemented($request, $id, RevisionInterface::class);
		}

		// dispatch revert event
		$this->get('event_dispatcher')->dispatch(RevisionEvents::REVERT, new RevisionEvent($entity, $entity->getRevision()));

		$manager = $this->getDoctrine()->getManager();
		$manager->persist($entity);
		$manager->flush();

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'success' => true,
				'id' => $entity->getId(),
				'message' => $this->get('translator')->trans('duo.core.revert_success', [], 'flashes')
			]);
		}

		$this->addFlash('success', $this->get('translator')->trans('duo.core.revert_success', [], 'flashes'));

		return $this->redirectToRoute("{$this->getRoutePrefix()}_update", [
			'id' => $entity->getId()
		]);
	}

	/**
	 * Revert action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	abstract public function revertAction(Request $request, int $id): Response;

	/**
	 * Get revision template
	 *
	 * @return string
	 */
	protected function getRevisionTemplate(): string
	{
		return '@DuoAdmin/Listing/revision.html.twig';
	}
}