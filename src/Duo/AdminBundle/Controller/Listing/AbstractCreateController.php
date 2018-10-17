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

		$eventDispatcher = $this->get('event_dispatcher');

		// dispatch pre create event
		$eventDispatcher->dispatch(EntityEvents::PRE_CREATE, new EntityEvent($entity));

		$form = $this->createForm($this->getFormType(), $entity);

		// dispatch pre create event
		$eventDispatcher->dispatch(FormEvents::PRE_CREATE, new FormEvent($form, $entity));

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			// dispatch post create event
			$eventDispatcher->dispatch(FormEvents::POST_CREATE, new FormEvent($form, $entity));

			$manager->persist($entity);
			$manager->flush();

			// dispatch post flush event
			$eventDispatcher->dispatch(ORMEvents::POST_FLUSH, new ORMEvent($entity));

			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return $this->json([
					'success' => true,
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