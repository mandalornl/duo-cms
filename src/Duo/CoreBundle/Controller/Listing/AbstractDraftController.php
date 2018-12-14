<?php

namespace Duo\CoreBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractController;
use Duo\CoreBundle\Entity\DraftInterface as EntityDraftInterface;
use Duo\CoreBundle\Entity\Property\VersionInterface;
use Duo\CoreBundle\Entity\Property\DraftInterface as PropertyDraftInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractDraftController extends AbstractController
{
	/**
	 * View action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response
	 *
	 * @throws \Throwable
	 */
	protected function doViewAction(Request $request, int $id): Response
	{
		$draft = $this->getDoctrine()->getRepository($this->getDraftEntityClass())->find($id);

		if ($draft === null)
		{
			return $this->entityNotFound($request, $id, $this->getDraftEntityClass());
		}

		if (!$draft instanceof EntityDraftInterface)
		{
			return $this->interfaceNotImplemented($request, $id, EntityDraftInterface::class, $this->getDraftEntityClass());
		}

		if (($entity = $draft->getEntity()) === null)
		{
			return $this->entityNotFound($request, -1);
		}

		// TODO: Investigate if there is no better way to disable form after submission.
		$form = $this->createForm($this->getFormType(), $entity);
		$form->submit($this->getFormData($draft, $form));

		$form = $this->createForm($this->getFormType(), $entity, [
			'action' => 'javascript:;',
			'disabled' => true
		]);

		return $this->render($this->getDraftTemplate(), (array)$this->getDefaultContext([
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
	 * @return Response
	 *
	 * @throws \Throwable
	 */
	abstract public function viewAction(Request $request, int $id): Response;

	/**
	 * Create action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|JsonResponse
	 *
	 * @throws \Throwable
	 */
	protected function doCreateAction(Request $request, int $id): Response
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		if (!$entity instanceof PropertyDraftInterface)
		{
			return $this->interfaceNotImplemented($request, $id, PropertyDraftInterface::class);
		}

		$form = $this->createForm($this->getFormType());

		if ($request->request->has($form->getName()) && $request->request->has('_draft'))
		{
			$manager = $this->getDoctrine()->getManager();

			/**
			 * @var EntityDraftInterface $draft
			 */
			$draft = $manager->getClassMetadata($this->getDraftEntityClass())->getReflectionClass()->newInstance();

			$draft
				->setName($request->request->get('_draft')['name'])
				->setEntity($entity)
				->setData(array_diff_key($request->request->get($form->getName(), []), [
					'version' => null,
					'_token' => null
				]));

			$manager->persist($draft);
			$manager->flush();

			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return $this->json([
					'success' => true,
					'message' => $this->get('translator')->trans('duo.admin.save_success', [], 'flashes')
				]);
			}

			$this->addFlash('success', $this->get('translator')->trans('duo.admin.save_success', [], 'flashes'));
		}
		else
		{
			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return $this->json([
					'success' => false,
					'message' => $this->get('translator')->trans('duo.admin.error', [], 'flashes')
				]);
			}

			$this->addFlash('danger', $this->get('translator')->trans('duo.admin.error', [], 'flashes'));
		}

		return $this->redirectToRoute("{$this->getRoutePrefix()}_update", [
			'id' => $entity->getId()
		]);
	}

	/**
	 * Create action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|JsonResponse
	 *
	 * @throws \Throwable
	 */
	abstract public function createAction(Request $request, int $id): Response;

	/**
	 * Apply action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|JsonResponse
	 *
	 * @throws \Throwable
	 */
	protected function doApplyAction(Request $request, int $id): Response
	{
		$draft = $this->getDoctrine()->getRepository($this->getDraftEntityClass())->find($id);

		if ($draft === null)
		{
			return $this->entityNotFound($request, $id, $this->getDraftEntityClass());
		}

		if (!$draft instanceof EntityDraftInterface)
		{
			return $this->interfaceNotImplemented($request, $id, EntityDraftInterface::class, $this->getDraftEntityClass());
		}

		if (($entity = $draft->getEntity()) === null)
		{
			return $this->entityNotFound($request, -1);
		}

		if (($route = $this->get('router')->getRouteCollection()->get("{$this->getRoutePrefix()}_update")) === null)
		{
			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return $this->json([
					'success' => false,
					'message' => $this->get('translator')->trans('duo.admin.error', [], 'flashes')
				]);
			}

			$this->addFlash('danger', $this->get('translator')->trans('duo.admin.error', [], 'flashes'));

			return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
		}

		$form = $this->createForm($this->getFormType());

		$request->setMethod('post');
		$request->request->set($form->getName(), $this->getFormData($draft, $form));

		$response = $this->forward($route->getDefault('_controller'), [
			'request' => $request,
			'id' => $entity->getId()
		]);

		// redirect to entity instead of index
		if ($response->isRedirection() &&
			$response->isRedirect($this->generateUrl("{$this->getRoutePrefix()}_index")))
		{
			return $this->redirectToRoute("{$this->getRoutePrefix()}_update", [
				'id' => $entity->getId()
			]);
		}

		return $response;
	}

	/**
	 * Apply action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|JsonResponse
	 *
	 * @throws \Throwable
	 */
	abstract public function applyAction(Request $request, int $id): Response;

	/**
	 * Destroy action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|JsonResponse
	 *
	 * @throws \Throwable
	 */
	protected function doDestroyAction(Request $request, int $id): Response
	{
		$entity = $this->getDoctrine()->getRepository($this->getDraftEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id, $this->getDraftEntityClass());
		}

		if (!$entity instanceof EntityDraftInterface)
		{
			return $this->interfaceNotImplemented($request, $id, EntityDraftInterface::class, $this->getDraftEntityClass());
		}

		$manager = $this->getDoctrine()->getManager();
		$manager->remove($entity);
		$manager->flush();

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'success' => true,
				'message' => $this->get('translator')->trans('duo.admin.destroy_success', [], 'flashes')
			]);
		}

		$this->addFlash('success', $this->get('translator')->trans('duo.admin.destroy_success', [], 'flashes'));

		if (($entity = $entity->getEntity()) !== null)
		{
			return $this->redirectToRoute("{$this->getRoutePrefix()}_update", [
				'id' => $entity->getId()
			]);
		}

		return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
	}

	/**
	 * Destroy action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|JsonResponse
	 *
	 * @throws \Throwable
	 */
	abstract public function destroyAction(Request $request, int $id): Response;

	/**
	 * Get draft template
	 *
	 * @return string
	 */
	protected function getDraftTemplate(): string
	{
		return '@DuoAdmin/Listing/draft.html.twig';
	}

	/**
	 * Get draft entity class.
	 *
	 * @return string
	 */
	protected function getDraftEntityClass(): string
	{
		return "{$this->getEntityClass()}Draft";
	}

	/**
	 * Get form data
	 *
	 * @param EntityDraftInterface $draft
	 * @param FormInterface $form
	 *
	 * @return array
	 */
	protected function getFormData(EntityDraftInterface $draft, FormInterface $form): array
	{
		$data = [
			'_token' => (string)$this->get('security.csrf.token_manager')->getToken($form->getName())
		];

		$entity = $draft->getEntity();

		if ($entity instanceof VersionInterface)
		{
			$data['version'] = $entity->getVersion();
		}

		return array_merge($draft->getData(), $data);
	}
}
