<?php

namespace Duo\CoreBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractController;
use Duo\AdminBundle\Tools\ORM\ClassMetadata;
use Duo\CoreBundle\Entity\Property\VersionInterface;
use Duo\CoreBundle\Entity\RevisionInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractRevisionController extends AbstractController
{
	/**
	 * View action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|JsonResponse
	 *
	 * @throws \Throwable
	 */
	protected function doViewAction(Request $request, int $id): Response
	{
		$revision = $this->getDoctrine()->getRepository($this->getRevisionEntityClass())->find($id);

		if ($revision === null)
		{
			return $this->entityNotFound($request, $id, $this->getRevisionEntityClass());
		}

		if (!$revision instanceof RevisionInterface)
		{
			return $this->interfaceNotImplemented($request, $id, RevisionInterface::class, $this->getRevisionEntityClass());
		}

		if (($entity = $revision->getEntity()) === null)
		{
			return $this->entityNotFound($request, -1);
		}

		// TODO: Investigate if there is no better way to disable form after submission.
		$form = $this->createForm($this->getFormType(), $entity);
		$form->submit($this->getFormData($revision, $form));

		$form = $this->createForm($this->getFormType(), $entity, [
			'action' => 'javascript:;',
			'disabled' => true
		]);

		return $this->render($this->getRevisionTemplate(), (array)$this->getDefaultContext([
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
	 * @return Response|JsonResponse
	 *
	 * @throws \Throwable
	 */
	abstract public function viewAction(Request $request, int $id): Response;

	/**
	 * Revert action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	protected function doRevertAction(Request $request, int $id): Response
	{
		$revision = $this->getDoctrine()->getRepository($this->getRevisionEntityClass())->find($id);

		if ($revision === null)
		{
			return $this->entityNotFound($request, $id, $this->getRevisionEntityClass());
		}

		if (!$revision instanceof RevisionInterface)
		{
			return $this->interfaceNotImplemented($request, $id, RevisionInterface::class, $this->getRevisionEntityClass());
		}

		if (($entity = $revision->getEntity()) === null)
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
		$request->request->set($form->getName(), $this->getFormData($revision, $form));

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
	 * Revert action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	abstract public function revertAction(Request $request, int $id): Response;

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
		$entity = $this->getDoctrine()->getRepository($this->getRevisionEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id, $this->getRevisionEntityClass());
		}

		if (!$entity instanceof RevisionInterface)
		{
			return $this->interfaceNotImplemented($request, $id, RevisionInterface::class, $this->getRevisionEntityClass());
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
	 * Get revision template
	 *
	 * @return string
	 */
	protected function getRevisionTemplate(): string
	{
		return '@DuoAdmin/Listing/revision.html.twig';
	}

	/**
	 * Get revision class name
	 *
	 * @return string
	 */
	protected function getRevisionEntityClass(): string
	{
		return ClassMetadata::getOneToManyTargetEntity($this->getEntityReflectionClass(), 'Revision');
	}

	/**
	 * Get form data
	 *
	 * @param RevisionInterface $revision
	 * @param FormInterface $form
	 *
	 * @return array
	 */
	protected function getFormData(RevisionInterface $revision, FormInterface $form): array
	{
		$data = [
			'_token' => (string)$this->get('security.csrf.token_manager')->getToken($form->getName())
		];

		$entity = $revision->getEntity();

		if ($entity instanceof VersionInterface)
		{
			$data['version'] = $entity->getVersion();
		}

		return array_merge($revision->getData(), $data);
	}
}
