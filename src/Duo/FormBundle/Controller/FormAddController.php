<?php

namespace Duo\FormBundle\Controller;

use Duo\AdminBundle\Controller\AbstractAddController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/form", name="duo_form_listing_form_")
 */
class FormAddController extends AbstractAddController
{
	use FormConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/add", name="add", methods={ "GET", "POST" })
	 */
	public function addAction(Request $request): Response
	{
		return $this->doAddAction($request);
	}
}