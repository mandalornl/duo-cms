<?php

namespace Duo\TaxonomyBundle\Controller;

use Duo\AdminBundle\Controller\AbstractCreateController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/module/taxonomy", name="duo_taxonomy_listing_taxonomy_")
 */
class TaxonomyCreateController extends AbstractCreateController
{
	use TaxonomyConfigurationTrait;

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