<?php

namespace Duo\BehaviorBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

interface PublishInterface
{
	/**
	 * Publish action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 */
	public function publishAction(Request $request, int $id);

	/**
	 * Unpublish action
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return RedirectResponse|JsonResponse
	 */
	public function unpublishAction(Request $request, int $id);
}