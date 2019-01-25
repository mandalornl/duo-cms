<?php

namespace Duo\CoreBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractController;
use Duo\CoreBundle\Entity\Property\SortInterface;
use Duo\CoreBundle\Entity\Property\TreeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractTreeController extends AbstractController
{
	/**
	 * Index action
	 *
	 * @param Request $request
	 *
	 * @return Response
	 *
	 * @throws \Throwable
	 */
	protected function doIndexAction(Request $request): Response
	{
		return $this->render('@DuoAdmin/Tree/view.html.twig', (array)$this->getDefaultContext([
			'children' => $this->getChildren($request),
			'moveToUrl' => $this->generateUrl("{$this->getRoutePrefix()}_move_to", [
				'_format' => 'json'
			]),
			'isSortable' => $this->isSortable()
		]));
	}

	/**
	 * Index action
	 *
	 * @param Request $request
	 *
	 * @return Response
	 *
	 * @throws \Throwable
	 */
	abstract public function indexAction(Request $request): Response;

	/**
	 * Children action
	 *
	 * @param Request $request
	 * @param RouterInterface $router
	 * @param int $id
	 *
	 * @return JsonResponse
	 *
	 * @throws \Throwable
	 */
	protected function doChildrenAction(Request $request, RouterInterface $router, int $id): JsonResponse
	{
		$entity = $this->getDoctrine()->getRepository($this->getEntityClass())->find($id);

		if ($entity === null)
		{
			return $this->entityNotFound($request, $id);
		}

		if (!$entity instanceof TreeInterface)
		{
			return $this->interfaceNotImplemented($request, $id, TreeInterface::class);
		}

		return $this->json([
			'html' => $this->renderView('@DuoAdmin/Tree/tree.html.twig', [
				'children' => $this->getChildren($request, $entity),
				'parent' => $entity,
				'routePrefix' => $this->getRoutePrefix(),
				'canEdit' => $router->getRouteCollection()->get("{$this->getRoutePrefix()}_update") !== null
			])
		]);
	}

	/**
	 * Is sortable
	 *
	 * @return bool
	 */
	protected function isSortable(): bool
	{
		return $this->getEntityReflectionClass()->implementsInterface(SortInterface::class);
	}

	/**
	 * Children action
	 *
	 * @param Request $request
	 * @param RouterInterface $router
	 * @param int $id
	 *
	 * @return JsonResponse
	 *
	 * @throws \Throwable
	 */
	abstract public function childrenAction(Request $request, RouterInterface $router, int $id): JsonResponse;

	/**
	 * Get children
	 *
	 * @param Request $request
	 * @param TreeInterface $parent [optional]
	 *
	 * @return TreeInterface[]
	 */
	abstract protected function getChildren(Request $request, TreeInterface $parent = null): array;
}
