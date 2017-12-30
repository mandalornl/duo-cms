<?php

namespace Duo\BehaviorBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface SortInterface
{
	/**
	 * Move entity up
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	public function moveUpAction(Request $request, int $id);

	/**
	 * Move entity down
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	public function moveDownAction(Request $request, int $id);

	/**
	 * Move entity to
	 *
	 * @param Request $request
	 * @param int $id
	 * @param int $weight
	 * @param int $parentId [optional]
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	public function moveToAction(Request $request, int $id, int $weight, int $parentId = null);
}