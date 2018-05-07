<?php

namespace Duo\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
	protected function doDestroyAction(Request $request, int $id = null): Response
	{
		return $this->handleDestroyRequest($request, $id);
	}

	/**
	 * Destroy entity
	 *
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return RedirectResponse|JsonResponse
	 *
	 * @throws \Throwable
	 */
	abstract public function destroyAction(Request $request, int $id = null): Response;

	/**
	 * Handle destroy request
	 *
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return Response
	 *
	 * @throws \Throwable
	 */
	private function handleDestroyRequest(Request $request, int $id = null): Response
	{
		$selection = (array)$id ?: $request->get('ids');

		if (!count($selection))
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
			$em = $this->getDoctrine()->getManager();

			foreach (array_chunk($selection, 100) as $ids)
			{
				$entities = $this->getDoctrine()->getRepository($this->getEntityClass())->findBy([
					'id' => $ids
				]);

				foreach ($entities as $entity)
				{
					$em->remove($entity);
				}

				$em->flush();
				$em->clear();
			}

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
		}

		return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
	}
}