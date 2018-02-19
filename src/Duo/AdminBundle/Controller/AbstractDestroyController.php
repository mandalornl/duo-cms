<?php

namespace Duo\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractDestroyController extends AbstractController
{
	/**
	 * Destroy entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	protected function doDestroyAction(Request $request, int $id = null)
	{
		if ($id === null)
		{
			return $this->handleMultiDestroyRequest($request);
		}

		return $this->handleDestroyRequest($request, $id);
	}

	/**
	 * Handle destroy request
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return JsonResponse|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	protected function handleDestroyRequest(Request $request, int $id)
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		$em = $this->getDoctrine()->getManager();
		$em->remove($entity);
		$em->flush();

		// reply with json response
		if ($request->getRequestFormat() === 'json')
		{
			return $this->json([
				'success' => true,
				'message' => $this->get('translator')->trans('duo.admin.listing.alert.delete_success')
			]);
		}

		$this->addFlash('success', $this->get('translator')->trans('duo.admin.listing.alert.delete_success'));

		return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
	}

	/**
	 * Handle multi destroy request
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	protected function handleMultiDestroyRequest(Request $request)
	{
		if (!count($ids = $request->get('ids')))
		{
			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return $this->json([
					'success' => false,
					'message' => $this->get('translator')->trans('duo.admin.listing.alert.no_items')
				]);
			}

			$this->addFlash('warning', $this->get('translator')->trans('duo.admin.listing.alert.no_items'));
		}
		else
		{
			/**
			 * @var EntityManager $em
			 */
			$em = $this->getDoctrine()->getManager();

			$em->createQueryBuilder()
				->delete($this->getEntityClass(), 'e')
				->where('e.id IN(:ids)')
				->setParameter('ids', $ids)
				->getQuery()
				->execute();

			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return $this->json([
					'success' => true,
					'message' => $this->get('translator')->trans('duo.admin.listing.alert.delete_success')
				]);
			}

			$this->addFlash('success', $this->get('translator')->trans('duo.admin.listing.alert.delete_success'));
		}

		return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
	}

	/**
	 * Destroy entity
	 *
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return RedirectResponse|JsonResponse
	 */
	abstract public function destroyAction(Request $request, int $id = null);
}