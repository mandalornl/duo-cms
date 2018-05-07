<?php

namespace Duo\FormBundle\Controller;

use Duo\AdminBundle\Controller\AbstractDuplicateController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/form", name="duo_form_listing_form_")
 */
class FormDuplicateController extends AbstractDuplicateController
{
	use FormConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/duplicate/{id}", name="duplicate", requirements={ "id" = "\d+" }, methods={ "GET", "POST" })
	 */
	public function duplicateAction(Request $request, int $id): Response
	{
		return $this->doDuplicateAction($request, $id);
	}
}