<?php

namespace Duo\FormBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractCreateController;
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
	 * @Route(
	 *     path="/create.{_format}",
	 *     name="create",
	 *     requirements={ "_format" = "html|json" },
	 *     defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function createAction(Request $request): Response
	{
		return $this->doCreateAction($request);
	}
}