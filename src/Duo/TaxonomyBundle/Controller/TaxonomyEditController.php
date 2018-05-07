<?php

namespace Duo\TaxonomyBundle\Controller;

use Duo\AdminBundle\Controller\AbstractEditController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/module/taxonomy", name="duo_taxonomy_listing_taxonomy_")
 */
class TaxonomyEditController extends AbstractEditController
{
	use TaxonomyConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/{id}", name="edit", requirements={ "id" = "\d+" }, methods={ "GET", "POST" })
	 */
	public function editAction(Request $request, int $id): Response
	{
		return $this->doEditAction($request, $id);
	}
}