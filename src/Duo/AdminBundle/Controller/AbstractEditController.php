<?php

namespace Duo\AdminBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Duo\AdminBundle\Configuration\Action\ItemActionInterface;
use Duo\AdminBundle\Event\ListingORMEvent;
use Duo\AdminBundle\Event\ListingORMEvents;
use Duo\AdminBundle\Event\ListingFormEvent;
use Duo\AdminBundle\Event\ListingFormEvents;
use Duo\AdminBundle\Event\TwigEvent;
use Duo\AdminBundle\Event\TwigEvents;
use Duo\BehaviorBundle\Entity\RevisionInterface;
use Duo\BehaviorBundle\Entity\VersionInterface;
use Duo\BehaviorBundle\Event\RevisionEvent;
use Duo\BehaviorBundle\Event\RevisionEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractEditController extends AbstractController
{
	/**
	 * @var ArrayCollection
	 */
	protected $itemActions;

	/**
	 * AbstractEditController constructor
	 */
	public function __construct()
	{
		$this->itemActions = new ArrayCollection();

		$this->defineItemActions();
	}

	/**
	 * Edit entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	protected function doEditAction(Request $request, int $id)
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		// handle entity revision
		if ($entity instanceof RevisionInterface)
		{
			return $this->handleEditRevisionRequest($request, $entity);
		}

		return $this->handleEditEntityRequest($request, $entity);
	}

	/**
	 * Handle edit entity request
	 *
	 * @param Request $request
	 * @param object $entity
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	protected function handleEditEntityRequest(Request $request, $entity)
	{
		/**
		 * @var EventDispatcherInterface $eventDispatcher
		 */
		$eventDispatcher = $this->get('event_dispatcher');

		$form = $this->createForm($this->getFormType(), $entity);

		// dispatch pre edit event
		$eventDispatcher->dispatch(ListingFormEvents::PRE_EDIT, new ListingFormEvent($entity, $form));

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			// dispatch post edit event
			$eventDispatcher->dispatch(ListingFormEvents::POST_EDIT, new ListingFormEvent($entity, $form));

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

				// dispatch pre flush event
				$eventDispatcher->dispatch(ListingORMEvents::POST_FLUSH, new ListingORMEvent($entity));

				$this->addFlash('success', $this->get('translator')->trans('duo.admin.listing.alert.save_success'));
			}
			catch (OptimisticLockException $e)
			{
				$this->addFlash('warning', $this->get('translator')->trans('duo.admin.listing.alert.locked'));
			}

			return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
		}

		$context = $this->getDefaultContext([
			'form' => $form->createView(),
			'entity' => $entity,
			'actions' => $this->getItemActions()
		]);

		// dispatch twig context event
		$eventDispatcher->dispatch(TwigEvents::CONTEXT, new TwigEvent($context));

		return $this->render($this->getEditTemplate(), (array)$context);
	}

	/**
	 * Handle edit revision request
	 *
	 * @param Request $request
	 * @param RevisionInterface $entity
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	protected function handleEditRevisionRequest(Request $request, RevisionInterface $entity)
	{
		$clone = clone $entity;

		/**
		 * @var EventDispatcherInterface $eventDispatcher
		 */
		$eventDispatcher = $this->get('event_dispatcher');

		$form = $this->createForm($this->getFormType(), $clone);

		// dispatch pre edit event
		$eventDispatcher->dispatch(ListingFormEvents::PRE_EDIT, new ListingFormEvent($clone, $form));

		// pre submit state
		$preSubmitState = serialize($clone);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			// dispatch post edit event
			$eventDispatcher->dispatch(ListingFormEvents::POST_EDIT, new ListingFormEvent($clone, $form));

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
					$eventDispatcher->dispatch(ListingORMEvents::POST_FLUSH, new ListingORMEvent($clone));

					$this->addFlash('success', $this->get('translator')->trans('duo.admin.listing.alert.save_success'));

					return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
				}
				catch (OptimisticLockException $e)
				{
					$this->addFlash('warning', $this->get('translator')->trans('duo.admin.listing.alert.locked'));
				}
			}
		}

		// redirect to latest revision
		if ($entity->getRevision() !== $entity)
		{
			return $this->redirectToRoute("{$this->getRoutePrefix()}_edit", [
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

		return $this->render($this->getEditTemplate(), (array)$context);
	}

	/**
	 * Edit entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse
	 */
	abstract public function editAction(Request $request, int $id);

	/**
	 * Get edit template
	 *
	 * @return string
	 */
	protected function getEditTemplate(): string
	{
		return '@DuoAdmin/Listing/edit.html.twig';
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