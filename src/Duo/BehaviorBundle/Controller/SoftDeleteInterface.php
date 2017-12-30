<?php

namespace Duo\BehaviorBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface SoftDeleteInterface
{
	/**
	 * Delete entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	public function deleteAction(Request $request, int $id);

	/**
	 * Undelete entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	public function undeleteAction(Request $request, int $id);
}