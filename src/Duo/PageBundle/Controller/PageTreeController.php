<?php

namespace Duo\PageBundle\Controller;

use Duo\PageBundle\Entity\Page;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(name="duo_page_tree_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class PageTreeController extends Controller
{
	/**
	 * Tree action
	 *
	 * @Route("/menu", name="menu")
	 * @Method("GET")
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function treeAction(Request $request): Response
	{
		$pages = $this->getDoctrine()->getRepository(Page::class)->findBy([
			'parent' => null
		]);

		return $this->render('@DuoPage/Menu/view.html.twig', [
			'menu' => $this->get('duo.admin.menu_builder')->createView(),
			'pages' => $pages
		]);
	}
}