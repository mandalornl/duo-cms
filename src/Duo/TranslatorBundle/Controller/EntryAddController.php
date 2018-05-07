<?php

namespace Duo\TranslatorBundle\Controller;

use Duo\AdminBundle\Controller\AbstractAddController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/settings/translation", name="duo_translator_listing_entry_")
 */
class EntryAddController extends AbstractAddController
{
	use EntryConfigurationTrait;

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