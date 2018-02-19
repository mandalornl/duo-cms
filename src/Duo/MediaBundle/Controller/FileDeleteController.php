<?php

namespace Duo\MediaBundle\Controller;

use Duo\AdminBundle\Controller\AbstractDeleteController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/media/file", name="duo_media_listing_file_")
 */
class FileDeleteController extends AbstractDeleteController
{
	use FileConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/delete/{id}", name="delete", requirements={ "id" = "\d+" })
	 * @Method({"GET", "POST"})
	 */
	public function deleteAction(Request $request, int $id = null)
	{
		return $this->doDeleteAction($request, $id);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/undelete/{id}", name="undelete", requirements={ "id" = "\d+" })
	 * @Method({"GET", "POST"})
	 */
	public function undeleteAction(Request $request, int $id = null)
	{
		return $this->doUndeleteAction($request, $id);
	}
}