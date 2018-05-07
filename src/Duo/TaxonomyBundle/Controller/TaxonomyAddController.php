<?php

namespace Duo\TaxonomyBundle\Controller;

use Duo\AdminBundle\Controller\AbstractAddController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/module/taxonomy", name="duo_taxonomy_listing_taxonomy_")
 */
class TaxonomyAddController extends AbstractAddController
{
	use TaxonomyConfigurationTrait;

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