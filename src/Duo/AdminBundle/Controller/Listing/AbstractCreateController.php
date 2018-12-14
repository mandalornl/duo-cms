<?php

namespace Duo\AdminBundle\Controller\Listing;

use Duo\AdminBundle\Event\Listing\EntityEvent;
use Duo\AdminBundle\Event\Listing\EntityEvents;
use Duo\AdminBundle\Event\Listing\FormEvent;
use Duo\AdminBundle\Event\Listing\FormEvents;
use Duo\AdminBundle\Event\Listing\ORMEvent;
use Duo\AdminBundle\Event\Listing\ORMEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractCreateController extends AbstractController
{
	/**
	 * Create entity
	 *
	 * @param Request $request
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	protected function doCreateAction(Request $request): Response
	{
		$manager = $this->getDoctrine()->getManager();

		$entity = $manager->getClassMetadata($this->getEntityClass())->getReflectionClass()->newInstance();

		// dispatch pre create event
		$this->get('event_dispatcher')->dispatch(EntityEvents::PRE_CREATE, new EntityEvent($entity));

		$form = $this->createForm($this->getFormType(), $entity);

		// dispatch pre create event
		$this->get('event_dispatcher')->dispatch(FormEvents::PRE_CREATE, ($formEvent = new FormEvent($form, $entity, $request)));

		// return when response is given
		if ($formEvent->hasResponse())
		{
			return $formEvent->getResponse();
		}

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			// dispatch post create event
			$this->get('event_dispatcher')->dispatch(FormEvents::POST_CREATE, ($formEvent = new FormEvent($form, $entity, $request)));

			// return when response is given
			if ($formEvent->hasResponse())
			{
				return $formEvent->getResponse();
			}

			$manager->persist($entity);
			$manager->flush();

			// dispatch post flush event
			$this->get('event_dispatcher')->dispatch(ORMEvents::POST_FLUSH, new ORMEvent($entity));

			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return $this->json([
					'id' => $entity->getId(),
					'message' => $this->get('translator')->trans('duo.admin.save_success', [], 'flashes')
				]);
			}

			$this->addFlash('success', $this->get('translator')->trans('duo.admin.save_success', [], 'flashes'));

			return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
		}

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'html' => $this->renderView('@DuoAdmin/Listing/form.html.twig', [
					'form' => $form->createView()
				])
			]);
		}

		return $this->render($this->getCreateTemplate(), (array)$this->getDefaultContext([
			'form' => $form->createView(),
			'entity' => $entity
		]));
	}

	/**
	 * Create entity
	 *
	 * @param Request $request
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	abstract public function createAction(Request $request): Response;

	/**
	 * Get create template
	 *
	 * @return string
	 */
	protected function getCreateTemplate(): string
	{
		return '@DuoAdmin/Listing/create.html.twig';
	}
}
