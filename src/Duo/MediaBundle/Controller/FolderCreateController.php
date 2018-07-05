<?php

namespace Duo\MediaBundle\Controller;

use Duo\AdminBundle\Controller\AbstractCreateController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media/folder", name="duo_media_listing_folder_")
 */
class FolderCreateController extends AbstractCreateController
{
	use FolderConfigurationTrait;

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