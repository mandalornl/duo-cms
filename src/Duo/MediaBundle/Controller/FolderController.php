<?php

namespace Duo\MediaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(name="duo_media_folder_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class FolderController extends Controller
{
	use FilePaginatorTrait;

	/**
	 * Index
	 *
	 * @Route("/", name="index", defaults={ "id" = null })
	 * @Route("/{id}", requirements={ "id" = "\d+" })
	 * @Method("GET")
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response
	 */
	public function indexAction(Request $request, int $id = null): Response
	{
		return $this->render('@DuoMedia/Listing/folder.html.twig', [
			'folders' => [],
			'files' => []
		]);
	}

	public function addAction(Request $request)
	{

	}

	public function editAction(Request $request, int $id)
	{

	}

	public function destroyAction(Request $request, int $id)
	{

	}
}