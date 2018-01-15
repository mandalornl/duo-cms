<?php

namespace Duo\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="duo_admin_dashboard_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class DashboardController extends Controller
{
	/**
	 * Index
	 *
	 * @Route("/", name="index")
	 * @Method("GET")
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function indexAction(Request $request): Response
	{
		return $this->render('@DuoAdmin/index.html.twig', [
			'menu' => $this->get('duo.admin.menu_builder')->createView()
		]);
	}
}