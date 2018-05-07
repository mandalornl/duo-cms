<?php

namespace Duo\MediaBundle\Controller;

use Duo\AdminBundle\Controller\AbstractDeleteController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media/folder", name="duo_media_listing_folder_")
 */
class FolderDeleteController extends AbstractDeleteController
{
	use FolderConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/delete/{id}", name="delete", requirements={ "id" = "\d+" }, methods={ "GET", "POST" })
	 */
	public function deleteAction(Request $request, int $id = null): Response
	{
		return $this->doDeleteAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/undelete/{id}", name="undelete", requirements={ "id" = "\d+" }, methods={ "GET", "POST" })
	 */
	public function undeleteAction(Request $request, int $id = null): Response
	{
		return $this->doUndeleteAction($request, $id);
	}
}