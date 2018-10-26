<?php

namespace Duo\AdminBundle\Controller\Listing;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Duo\AdminBundle\Configuration\Action\ItemActionInterface;
use Duo\AdminBundle\Event\Listing\EntityEvent;
use Duo\AdminBundle\Event\Listing\EntityEvents;
use Duo\AdminBundle\Event\Listing\FormEvent;
use Duo\AdminBundle\Event\Listing\FormEvents;
use Duo\AdminBundle\Event\Listing\ORMEvent;
use Duo\AdminBundle\Event\Listing\ORMEvents;
use Duo\AdminBundle\Event\Listing\TwigEvent;
use Duo\AdminBundle\Event\Listing\TwigEvents;
use Duo\AdminBundle\Tools\Form\Form;
use Duo\CoreBundle\Entity\Property\RevisionInterface as PropertyRevisionInterface;
use Duo\CoreBundle\Entity\Property\VersionInterface;
use Duo\CoreBundle\Entity\RevisionInterface as EntityRevisionInterface;
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

		if ($request->query->has('test'))
		{
			return $this->json($entity->getRevisions()->first()->getData());
		}

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		$eventDispatcher = $this->get('event_dispatcher');

		// dispatch pre update event
		$eventDispatcher->dispatch(EntityEvents::PRE_UPDATE, new EntityEvent($entity));

		$form = $this->createForm($this->getFormType(), $entity);

		// pre submit state
		$preSubmitState = Form::getViewData($form);

		// dispatch pre update event
		$eventDispatcher->dispatch(FormEvents::PRE_UPDATE, ($formEvent = new FormEvent($form, $entity, $request)));

		// return when response is given
		if ($formEvent->hasResponse())
		{
			return $formEvent->getResponse();
		}

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			// dispatch post update event
			$eventDispatcher->dispatch(FormEvents::POST_UPDATE, ($formEvent = new FormEvent($form, $entity, $request)));

			// return when response is given
			if ($formEvent->hasResponse())
			{
				return $formEvent->getResponse();
			}

			// post submit state
			$postSubmitState = Form::getViewData($form);

			// check whether or not entity was modified, don't force unchanged revisions
			if ($preSubmitState === $postSubmitState)
			{
				// reply with json response
				if ($request->getRequestFormat() === 'json')
				{
					return $this->json([
						'success' => false,
						'id' => $entity->getId(),
						'message' => $this->get('translator')->trans('duo.admin.no_changes', [], 'flashes')
					]);
				}

				$this->addFlash('warning', $this->get('translator')->trans('duo.admin.no_changes', [], 'flashes'));

				return $this->redirectToRoute("{$this->getRoutePrefix()}_update", [
					'id' => $entity->getId()
				]);
			}

			try
			{
				/**
				 * @var EntityManagerInterface $manager
				 */
				$manager = $this->getDoctrine()->getManager();

				// check whether or not entity is locked
				if ($entity instanceof VersionInterface)
				{
					$manager->lock($entity, LockMode::OPTIMISTIC, $entity->getVersion());
				}

				$manager->persist($entity);

				// add revision
				if ($entity instanceof PropertyRevisionInterface)
				{
					$className = "{$manager->getRepository($this->getEntityClass())->getClassName()}Revision";

					/**
					 * @var EntityRevisionInterface $revision
					 */
					$revision = $manager->getClassMetadata($className)->getReflectionClass()->newInstance();
					$revision
						->setEntity($entity)
						->setData(array_diff_key($preSubmitState, [
							'version' => null,
							'_token' => null
						]));

					$manager->persist($revision);
				}

				$manager->flush();

				// dispatch post flush event
				$eventDispatcher->dispatch(ORMEvents::POST_FLUSH, new ORMEvent($entity));

				// reply with json response
				if ($request->getRequestFormat() === 'json')
				{
					return $this->json([
						'success' => true,
						'message' => $this->get('translator')->trans('duo.admin.save_success', [], 'flashes')
					]);
				}

				$this->addFlash('success', $this->get('translator')->trans('duo.admin.save_success', [], 'flashes'));

				return $this->redirectToRoute("{$this->getRoutePrefix()}_update", [
					'id' => $entity->getId()
				]);
			}
			catch (OptimisticLockException $e)
			{
				// reply with json response
				if ($request->getRequestFormat() === 'json')
				{
					return $this->json([
						'success' => false,
						'message' => $this->get('translator')->trans('duo.admin.locked', [], 'flashes')
					]);
				}

				$this->addFlash('warning', $this->get('translator')->trans('duo.admin.locked', [], 'flashes'));
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
	 * Handle update entity request
	 *
	 * @param Request $request
	 * @param object $entity
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	protected function handleUpdateEntityRequest(Request $request, object $entity): Response
	{
		$eventDispatcher = $this->get('event_dispatcher');

		// dispatch pre update event
		$eventDispatcher->dispatch(EntityEvents::PRE_UPDATE, new EntityEvent($entity));

		$form = $this->createForm($this->getFormType(), $entity);

		// dispatch pre update event
		$eventDispatcher->dispatch(FormEvents::PRE_UPDATE, ($formEvent = new FormEvent($form, $entity, $request)));

		// return when response is given
		if ($formEvent->hasResponse())
		{
			return $formEvent->getResponse();
		}

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			// dispatch post update event
			$eventDispatcher->dispatch(FormEvents::POST_UPDATE, ($formEvent = new FormEvent($form, $entity, $request)));

			// return when response is given
			if ($formEvent->hasResponse())
			{
				return $formEvent->getResponse();
			}

			try
			{
				/**
				 * @var EntityManagerInterface $manager
				 */
				$manager = $this->getDoctrine()->getManager();

				// check whether or not entity is locked
				if ($entity instanceof VersionInterface)
				{
					$manager->lock($entity, LockMode::OPTIMISTIC, $entity->getVersion());
				}

				$manager->persist($entity);
				$manager->flush();

				// dispatch post flush event
				$eventDispatcher->dispatch(ORMEvents::POST_FLUSH, new ORMEvent($entity));

				// reply with json response
				if ($request->getRequestFormat() === 'json')
				{
					return $this->json([
						'success' => true,
						'message' => $this->get('translator')->trans('duo.admin.save_success', [], 'flashes')
					]);
				}

				$this->addFlash('success', $this->get('translator')->trans('duo.admin.save_success', [], 'flashes'));

				return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
			}
			catch (OptimisticLockException $e)
			{
				// reply with json response
				if ($request->getRequestFormat() === 'json')
				{
					return $this->json([
						'success' => false,
						'message' => $this->get('translator')->trans('duo.admin.locked', [], 'flashes')
					]);
				}

				$this->addFlash('warning', $this->get('translator')->trans('duo.admin.locked', [], 'flashes'));
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