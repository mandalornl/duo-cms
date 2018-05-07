<?php

namespace Duo\TranslatorBundle\Controller;

use Duo\AdminBundle\Controller\AbstractEditController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/settings/translation", name="duo_translator_listing_entry_")
 */
class EntryEditController extends AbstractEditController
{
	use EntryConfigurationTrait;

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