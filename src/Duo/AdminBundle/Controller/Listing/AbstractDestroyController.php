<?php

namespace Duo\AdminBundle\Controller\Listing;

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
		$selection = (array)$id ?: $this->getSelection($request);

		if (!count($selection))
		{
			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return $this->json([
					'success' => false,
					'message' => $this->get('translator')->trans('duo_admin.no_items', [], 'flashes')
				]);
			}

			$this->addFlash('warning', $this->get('translator')->trans('duo_admin.no_items', [], 'flashes'));
		}
		else
		{
			$manager = $this->getDoctrine()->getManager();

			foreach (array_chunk($selection, 100) as $ids)
			{
				$entities = $this->getDoctrine()->getRepository($this->getEntityClass())->findBy([
					'id' => $ids
				]);

				foreach ($entities as $entity)
				{
					$manager->remove($entity);
				}

				$manager->flush();
				$manager->clear();
			}

			$manager->flush();

			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return $this->json([
					'success' => true,
					'message' => $this->get('translator')->trans('duo_admin.destroy_success', [], 'flashes')
				]);
			}

			$this->addFlash('success', $this->get('translator')->trans('duo_admin.destroy_success', [], 'flashes'));
		}

		return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
	}
}
