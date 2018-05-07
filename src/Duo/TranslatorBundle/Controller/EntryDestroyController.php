<?php

namespace Duo\TranslatorBundle\Controller;

use Duo\AdminBundle\Controller\AbstractDestroyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/settings/translation", name="duo_translator_listing_entry_")
 */
class EntryDestroyController extends AbstractDestroyController
{
	use EntryConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/destroy/{id}", name="destroy", requirements={ "id" = "\d+" }, methods={ "GET", "POST" })
	 */
	public function destroyAction(Request $request, int $id = null): Response
	{
		return $this->doDestroyAction($request, $id);
	}
}