<?php

namespace Duo\FormBundle\Controller;

use Duo\AdminBundle\Controller\AbstractCreateController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/form", name="duo_form_listing_form_")
 */
class FormCreateController extends AbstractCreateController
{
	use FormConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/create", name="create", methods={ "GET", "POST" })
	 */
	public function createAction(Request $request): Response
	{
		return $this->doCreateAction($request);
	}
}