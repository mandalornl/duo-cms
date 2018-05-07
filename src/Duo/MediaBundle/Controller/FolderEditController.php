<?php

namespace Duo\MediaBundle\Controller;

use Duo\AdminBundle\Controller\AbstractEditController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media/folder", name="duo_media_listing_folder_")
 */
class FolderEditController extends AbstractEditController
{
	use FolderConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/edit/{id}", name="edit", requirements={ "id" = "\d+" }, methods={ "GET", "POST" })
	 */
	public function editAction(Request $request, int $id): Response
	{

	}
}