<?php

namespace Duo\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="duo_admin_dashboard_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class DashboardController extends AbstractController
{
	/**
	 * Index
	 *
	 * @Route("/", name="index", methods={ "GET" })
	 *
	 * @return Response
	 */
	public function indexAction(): Response
	{
		return $this->render('@DuoAdmin/index.html.twig');
	}
}
