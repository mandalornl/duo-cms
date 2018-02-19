<?php

namespace Duo\AdminBundle\Controller;

use Duo\AdminBundle\Event\ListingEvent;
use Duo\AdminBundle\Event\ListingEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractAddController extends AbstractController
{
	/**
	 * Add entity
	 *
	 * @param Request $request
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	protected function doAddAction(Request $request)
	{
		$class = $this->getEntityClass();

		$entity = new $class();

		$eventDispatcher = $this->get('event_dispatcher');

		// dispatch pre add event
		$eventDispatcher->dispatch(ListingEvents::PRE_ADD, new ListingEvent($entity));

		$form = $this->createForm($this->getFormType(), $entity);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			// dispatch post add event
			$eventDispatcher->dispatch(ListingEvents::POST_ADD, new ListingEvent($entity, $form));

			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			$this->addFlash('success', $this->get('translator')->trans('duo.admin.listing.alert.save_success'));

			return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
		}

		return $this->render($this->getAddTemplate(), (array)$this->getDefaultContext([
			'form' => $form->createView(),
			'entity' => $entity
		]));
	}

	/**
	 * Add entity
	 *
	 * @param Request $request
	 *
	 * @return Response|RedirectResponse
	 */
	abstract public function addAction(Request $request);

	/**
	 * Get add template
	 *
	 * @return string
	 */
	protected function getAddTemplate(): string
	{
		return '@DuoAdmin/Listing/add.html.twig';
	}
}