<?php

namespace Duo\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends Controller
{
	/**
	 * Index
	 *
	 * @Route("/", name="duo_admin_dashboard")
	 * @Method("GET")
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function indexAction(Request $request): Response
	{
		return new Response('Dashboard');
	}
}