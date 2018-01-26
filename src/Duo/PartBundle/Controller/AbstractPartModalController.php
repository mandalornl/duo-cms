<?php

namespace Duo\PartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractPartModalController extends Controller
{
	/**
	 * Modal action
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	protected function doModalAction(Request $request): JsonResponse
	{
		$form = $this->createForm($this->getFormType());

		return $this->json(
			$this->renderView('@DuoPart/Modal/prototype.html.twig', [
				'form' => $form->createView()
			])
		);
	}

	/**
	 * Get form type
	 *
	 * @return string
	 */
	abstract protected function getFormType(): string;

	/**
	 * Modal action
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	abstract public function modelAction(Request $request): JsonResponse;
}