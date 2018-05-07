<?php

namespace Duo\MediaBundle\Controller;

use Duo\AdminBundle\Controller\AbstractDeleteController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/media/file", name="duo_media_listing_file_")
 */
class FileDeleteController extends AbstractDeleteController
{
	use FileConfigurationTrait;

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