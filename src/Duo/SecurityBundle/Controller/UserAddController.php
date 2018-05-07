<?php

namespace Duo\SecurityBundle\Controller;

use Duo\AdminBundle\Controller\AbstractAddController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/security/user", name="duo_security_listing_user_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_SUPER_ADMIN')")
 */
class UserAddController extends AbstractAddController
{
	use UserConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/add", name="add", methods={ "GET", "POST" })
	 */
	public function addAction(Request $request): Response
	{
		return $this->doAddAction($request);
	}
}