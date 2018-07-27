<?php

namespace Duo\AdminBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Duo\AdminBundle\Configuration\Action\ItemActionInterface;
use Duo\AdminBundle\Event\Listing\EntityEvent;
use Duo\AdminBundle\Event\Listing\EntityEvents;
use Duo\AdminBundle\Event\Listing\FormEvent;
use Duo\AdminBundle\Event\Listing\FormEvents;
use Duo\AdminBundle\Event\Listing\ORMEvent;
use Duo\AdminBundle\Event\Listing\ORMEvents;
use Duo\AdminBundle\Event\TwigEvent;
use Duo\AdminBundle\Event\TwigEvents;
use Duo\CoreBundle\Entity\RevisionInterface;
use Duo\CoreBundle\Entity\VersionInterface;
use Duo\CoreBundle\Event\RevisionEvent;
use Duo\CoreBundle\Event\RevisionEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractUpdateController extends AbstractController
{
	/**
	 * @var ArrayCollection
	 */
	protected $itemActions;

	/**
	 * AbstractUpdateController constructor
	 */
	public function __construct()
	{
		$this->itemActions = new ArrayCollection();

		$this->defineItemActions();
	}

	/**
	 * Update entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	protected function doUpdateAction(Request $request, int $id): Response
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		// handle entity revision
		if ($entity instanceof RevisionInterface)
		{
			return $this->handleUpdateRevisionRequest($request, $entity);
		}

		return $this->handleUpdateEntityRequest($request, $entity);
	}

	/**
	 * Handle update entity request
	 *
	 * @param Request $request
	 * @param mixed $entity
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	protected function handleUpdateEntityRequest(Request $request, $entity): Response
	{
		$eventDispatcher = $this->get('event_dispatcher');

		// dispatch pre update event
		$eventDispatcher->dispatch(EntityEvents::PRE_UPDATE, new EntityEvent($entity));

		$form = $this->createForm($this->getFormType(), $entity);

		// dispatch pre update event
		$eventDispatcher->dispatch(FormEvents::PRE_UPDATE, new FormEvent($form, $entity));

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			// dispatch post update event
			$eventDispatcher->dispatch(FormEvents::POST_UPDATE, new FormEvent($form, $entity));

			try
			{
				/**
				 * @var EntityManager $em
				 */
				$em = $this->getDoctrine()->getManager();

				// check whether or not entity is locked
				if ($entity instanceof VersionInterface)
				{
					$em->lock($entity, LockMode::OPTIMISTIC, $entity->getVersion());
				}

				$em->persist($entity);
				$em->flush();

				// dispatch post flush event
				$eventDispatcher->dispatch(ORMEvents::POST_FLUSH, new ORMEvent($entity));

				// reply with json response
				if ($request->getRequestFormat() === 'json')
				{
					return $this->json([
						'success' => true,
						'message' => $this->get('translator')->trans('duo.admin.listing.alert.save_success')
					]);
				}

				$this->addFlash('success', $this->get('translator')->trans('duo.admin.listing.alert.save_success'));

				return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
			}
			catch (OptimisticLockException $e)
			{
				// reply with json response
				if ($request->getRequestFormat() === 'json')
				{
					return $this->json([
						'success' => false,
						'message' => $this->get('translator')->trans('duo.admin.listing.alert.locked')
					]);
				}

				$this->addFlash('warning', $this->get('translator')->trans('duo.admin.listing.alert.locked'));
			}
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

		$context = $this->getDefaultContext([
			'form' => $form->createView(),
			'entity' => $entity,
			'actions' => $this->getItemActions()
		]);

		// dispatch twig context event
		$eventDispatcher->dispatch(TwigEvents::CONTEXT, new TwigEvent($context));

		return $this->render($this->getUpdateTemplate(), (array)$context);
	}

	/**
	 * Handle update revision request
	 *
	 * @param Request $request
	 * @param RevisionInterface $entity
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	protected function handleUpdateRevisionRequest(Request $request, RevisionInterface $entity): Response
	{
		$clone = clone $entity;

		$eventDispatcher = $this->get('event_dispatcher');

		// dispatch pre update event
		$eventDispatcher->dispatch(EntityEvents::PRE_UPDATE, new EntityEvent($clone));

		// pre submit state
		$preSubmitState = serialize($clone);

		$form = $this->createForm($this->getFormType(), $clone);

		// dispatch pre update event
		$eventDispatcher->dispatch(FormEvents::PRE_UPDATE, new FormEvent($form, $clone));

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			// dispatch post update event
			$eventDispatcher->dispatch(FormEvents::POST_UPDATE, new FormEvent($form, $clone));

			// post submit state
			$postSubmitState = serialize($clone);

			// check whether or not entity was modified, don't force unchanged revision
			if (strcmp($preSubmitState, $postSubmitState) !== 0)
			{
				// dispatch onClone event
				$eventDispatcher->dispatch(RevisionEvents::CLONE, new RevisionEvent($clone, $entity));

				try
				{
					/**
					 * @var EntityManager $em
					 */
					$em = $this->getDoctrine()->getManager();

					// check whether or not entity is locked
					if ($clone instanceof VersionInterface)
					{
						$em->lock($entity, LockMode::OPTIMISTIC, $clone->getVersion());
					}

					$em->persist($clone);
					$em->flush();

					// dispatch post flush event
					$eventDispatcher->dispatch(ORMEvents::POST_FLUSH, new ORMEvent($clone));

					// reply with json response
					if ($request->getRequestFormat() === 'json')
					{
						return $this->json([
							'success' => true,
							'message' => $this->get('translator')->trans('duo.admin.listing.alert.save_success')
						]);
					}

					$this->addFlash('success', $this->get('translator')->trans('duo.admin.listing.alert.save_success'));

					return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
				}
				catch (OptimisticLockException $e)
				{
					// reply with json response
					if ($request->getRequestFormat() === 'json')
					{
						return $this->json([
							'success' => false,
							'message' => $this->get('translator')->trans('duo.admin.listing.alert.locked')
						]);
					}

					$this->addFlash('warning', $this->get('translator')->trans('duo.admin.listing.alert.locked'));
				}
			}
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

		// redirect to latest revision
		if ($entity->getRevision() !== $entity)
		{
			return $this->redirectToRoute("{$this->getRoutePrefix()}_update", [
				'id' => $entity->getRevision()->getId()
			]);
		}

		$context = $this->getDefaultContext([
			'form' => $form->createView(),
			'entity' => $clone,
			'actions' => $this->getItemActions()
		]);

		// dispatch twig context event
		$eventDispatcher->dispatch(TwigEvents::CONTEXT, new TwigEvent($context));

		return $this->render($this->getUpdateTemplate(), (array)$context);
	}

	/**
	 * Update entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	abstract public function updateAction(Request $request, int $id): Response;

	/**
	 * Get update template
	 *
	 * @return string
	 */
	protected function getUpdateTemplate(): string
	{
		return '@DuoAdmin/Listing/update.html.twig';
	}

	/**
	 * Add item action
	 *
	 * @param ItemActionInterface $itemAction
	 *
	 * @return $this
	 */
	public function addItemAction(ItemActionInterface $itemAction)
	{
		$this->getItemActions()->add($itemAction);

		return $this;
	}

	/**
	 * Remove item action
	 *
	 * @param ItemActionInterface $itemAction
	 *
	 * @return $this
	 */
	public function removeItemAction(ItemActionInterface $itemAction)
	{
		$this->getItemActions()->removeElement($itemAction);

		return $this;
	}

	/**
	 * Get item actions
	 *
	 * @return ArrayCollection
	 */
	public function getItemActions(): ArrayCollection
	{
		return $this->itemActions;
	}

	/**
	 * Define item actions
	 */
	protected function defineItemActions(): void
	{
		// Implement defineItemActions() method.
	}
}