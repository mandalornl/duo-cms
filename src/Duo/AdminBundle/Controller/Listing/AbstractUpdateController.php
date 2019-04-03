<?php

namespace Duo\AdminBundle\Controller\Listing;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Duo\AdminBundle\Configuration\Action\ActionInterface;
use Duo\AdminBundle\Event\Listing\EntityEvent;
use Duo\AdminBundle\Event\Listing\EntityEvents;
use Duo\AdminBundle\Event\Listing\FormEvent;
use Duo\AdminBundle\Event\Listing\FormEvents;
use Duo\AdminBundle\Event\Listing\ORMEvent;
use Duo\AdminBundle\Event\Listing\ORMEvents;
use Duo\AdminBundle\Event\Listing\TwigEvent;
use Duo\AdminBundle\Event\Listing\TwigEvents;
use Duo\AdminBundle\Tools\Form\Form;
use Duo\AdminBundle\Tools\ORM\ClassMetadata;
use Duo\CoreBundle\Entity\Property\RevisionInterface as PropertyRevisionInterface;
use Duo\CoreBundle\Entity\Property\RevisionInterface;
use Duo\CoreBundle\Entity\Property\VersionInterface;
use Duo\CoreBundle\Entity\RevisionInterface as EntityRevisionInterface;
use Duo\CoreBundle\Repository\RevisionInterface as RepositoryRevisionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractUpdateController extends AbstractController
{
	/**
	 * @var ArrayCollection
	 */
	protected $actions;

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

		// dispatch pre update event
		$this->get('event_dispatcher')->dispatch(EntityEvents::PRE_UPDATE, ($event = new EntityEvent($entity, $request)));

		// return with response when given
		if ($event->hasResponse())
		{
			return $event->getResponse();
		}

		$form = $this->createForm($this->getFormType(), $entity);

		// pre submit state
		$preSubmitState = Form::getViewData($form);

		// dispatch pre update event
		$this->get('event_dispatcher')->dispatch(FormEvents::PRE_UPDATE, ($event = new FormEvent($form, $entity, $request)));

		// return with response when given
		if ($event->hasResponse())
		{
			return $event->getResponse();
		}

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			// dispatch post update event
			$this->get('event_dispatcher')->dispatch(FormEvents::POST_UPDATE, ($event = new FormEvent($form, $entity, $request)));

			// return with response when given
			if ($event->hasResponse())
			{
				return $event->getResponse();
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
						'message' => $this->get('translator')->trans('duo_admin.no_changes', [], 'flashes')
					]);
				}

				$this->addFlash('warning', $this->get('translator')->trans('duo_admin.no_changes', [], 'flashes'));

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
				if ($entity instanceof RevisionInterface &&
					($revision = $this->createRevision($entity, $preSubmitState)) !== null)
				{
					$manager->persist($revision);
				}

				$manager->flush();

				// dispatch post flush event
				$this->get('event_dispatcher')->dispatch(ORMEvents::POST_FLUSH, ($event = new ORMEvent($entity, $request)));

				// return with response when given
				if ($event->hasResponse())
				{
					return $event->getResponse();
				}

				// reply with json response
				if ($request->getRequestFormat() === 'json')
				{
					return $this->json([
						'success' => true,
						'message' => $this->get('translator')->trans('duo_admin.save_success', [], 'flashes')
					]);
				}

				$this->addFlash('success', $this->get('translator')->trans('duo_admin.save_success', [], 'flashes'));

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
						'message' => $this->get('translator')->trans('duo_admin.locked', [], 'flashes')
					]);
				}

				$this->addFlash('warning', $this->get('translator')->trans('duo_admin.locked', [], 'flashes'));
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

		// define properties
		$this->defineActions($request);

		$context = $this->createTwigContext([
			'form' => $form->createView(),
			'entity' => $entity,
			'actions' => $this->getActions()
		]);

		// dispatch twig context event
		$this->get('event_dispatcher')->dispatch(TwigEvents::CONTEXT, new TwigEvent($context));

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
	 * Add action
	 *
	 * @param ActionInterface $action
	 *
	 * @return $this
	 */
	public function addItemAction(ActionInterface $action)
	{
		$this->getActions()->add($action);

		return $this;
	}

	/**
	 * Remove action
	 *
	 * @param ActionInterface $action
	 *
	 * @return $this
	 */
	public function removeAction(ActionInterface $action)
	{
		$this->getActions()->removeElement($action);

		return $this;
	}

	/**
	 * Get actions
	 *
	 * @return ArrayCollection
	 */
	public function getActions(): ArrayCollection
	{
		return $this->actions = $this->actions ?: new ArrayCollection();
	}

	/**
	 * Define actions
	 *
	 * @param Request $request
	 */
	protected function defineActions(Request $request): void
	{
		// Implement defineItemActions() method.
	}

	/**
	 * Create revision
	 *
	 * @param PropertyRevisionInterface $entity
	 * @param array $data
	 *
	 * @return EntityRevisionInterface
	 */
	protected function createRevision(PropertyRevisionInterface $entity, array $data): ?EntityRevisionInterface
	{
		$className = ClassMetadata::getOneToManyTargetEntity($this->getEntityReflectionClass(), 'Revision');

		/**
		 * @var RepositoryRevisionInterface $repository
		 */
		$repository = $this->getDoctrine()->getRepository($className);

		$data = array_diff_key($data, [
			'version' => null,
			'_token' => null
		]);

		$name = md5(serialize($data));

		if ($repository->nameExists($name))
		{
			return null;
		}

		/**
		 * @var EntityRevisionInterface $revision
		 */
		$revision = $this->getDoctrine()->getManager()
			->getClassMetadata($className)
			->getReflectionClass()
			->newInstance();

		$revision
			->setName($name)
			->setEntity($entity)
			->setData($data);

		return $revision;
	}
}
