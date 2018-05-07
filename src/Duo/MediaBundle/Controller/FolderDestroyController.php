<?php

namespace Duo\MediaBundle\Controller;

use Duo\AdminBundle\Controller\AbstractDestroyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media/folder", name="duo_media_listing_folder_")
 */
class FolderDestroyController extends AbstractDestroyController
{
	use FolderConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/destroy/{id}", name="destroy", requirements={ "id" = "\d+" }, methods={ "GET", "POST" })
	 */
	public function destroyAction(Request $request, int $id = null): Response
	{

	}
}