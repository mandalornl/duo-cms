<?php

namespace Duo\DraftBundle\Controller;

use Duo\AdminBundle\Controller\AbstractController;
use Duo\CoreBundle\Entity\Property\VersionInterface;
use Duo\DraftBundle\Entity\DraftInterface as EntityDraftInterface;
use Duo\DraftBundle\Entity\Property\DraftInterface as PropertyDraftInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractDraftController extends AbstractController
{
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
					'message' => $this->get('translator')->trans('duo.admin.listing.alert.save_success')
				]);
			}

			$this->addFlash('success', $this->get('translator')->trans('duo.admin.listing.alert.save_success'));
		}
		else
		{
			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return $this->json([
					'success' => false,
					'message' => $this->get('translator')->trans('duo.admin.alert.error')
				]);
			}

			$this->addFlash('danger', $this->get('translator')->trans('duo.admin.alert.error'));
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
			return $this->entityNotFound($request, -1, $this->getEntityClass());
		}

		if (($route = $this->get('router')->getRouteCollection()->get("{$this->getRoutePrefix()}_update")) === null)
		{
			if ($request->getRequestFormat() === 'json')
			{
				return $this->json([
					'success' => false,
					'message' => $this->get('translator')->trans('duo.admin.alert.error')
				]);
			}

			$this->addFlash('danger', $this->get('translator')->trans('duo.admin.alert.error'));

			return $this->redirectToRoute("{$this->getRoutePrefix()}_update", [
				'id' => $entity->getId()
			]);
		}

		$form = $this->createForm($this->getFormType());

		$data = [
			'_token' => (string)$this->get('security.csrf.token_manager')->getToken($form->getName())
		];

		if ($entity instanceof VersionInterface)
		{
			$data['version'] = $entity->getVersion();
		}

		$request->setMethod('post');
		$request->request->set($form->getName(), array_merge($draft->getData(), $data));

		$response = $this->forward($route->getDefault('_controller'), [
			'request' => $request,
			'id' => $entity->getId()
		]);

		dump(
			$response->isSuccessful(),
			$response->isOk(),
			$response->isRedirection(),
			$response->isRedirect($this->generateUrl("{$this->getRoutePrefix()}_index")),
			$response,
			$entity->getRevision()->getId(),
			$entity->getId()
		);
		exit;

		// redirect to entity instead of index
		if ($response->isSuccessful() &&
			$response->isRedirection() &&
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
				'message' => $this->get('translator')->trans('duo.admin.listing.alert.delete_success')
			]);
		}

		$this->addFlash('success', $this->get('translator')->trans('duo.admin.listing.alert.delete_success'));

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
	 * Get draft entity class.
	 *
	 * @return string
	 */
	abstract protected function getDraftEntityClass(): string;
}