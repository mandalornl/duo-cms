<?php

namespace Duo\AdminBundle\Controller;

use Duo\AdminBundle\Event\Listing\EntityEvent;
use Duo\AdminBundle\Event\Listing\EntityEvents;
use Duo\AdminBundle\Event\Listing\FormEvent;
use Duo\AdminBundle\Event\Listing\FormEvents;
use Duo\AdminBundle\Event\Listing\ORMEvent;
use Duo\AdminBundle\Event\Listing\ORMEvents;
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
	protected function doAddAction(Request $request): Response
	{
		$class = $this->getEntityClass();

		$entity = new $class();

		$eventDispatcher = $this->get('event_dispatcher');

		// dispatch pre add event
		$eventDispatcher->dispatch(EntityEvents::PRE_ADD, new EntityEvent($entity));

		$form = $this->createForm($this->getFormType(), $entity);

		// dispatch pre add event
		$eventDispatcher->dispatch(FormEvents::PRE_ADD, new FormEvent($form));

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			// dispatch post add event
			$eventDispatcher->dispatch(EntityEvents::POST_ADD, new EntityEvent($entity));

			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			// dispatch post flush event
			$eventDispatcher->dispatch(ORMEvents::POST_FLUSH, new ORMEvent($entity));

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
	 *
	 * @throws \Throwable
	 */
	abstract public function addAction(Request $request): Response;

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