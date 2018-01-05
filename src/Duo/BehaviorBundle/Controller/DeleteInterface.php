<?php

namespace Duo\BehaviorBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

interface DeleteInterface
{
	/**
	 * Delete entity
	 *
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return RedirectResponse|JsonResponse
	 */
	public function deleteAction(Request $request, int $id = null);

	/**
	 * Undelete entity
	 *
	 * @param Request $request
	 * @param int $id [optional]
	 *
	 * @return RedirectResponse|JsonResponse
	 */
	public function undeleteAction(Request $request, int $id = null);
}