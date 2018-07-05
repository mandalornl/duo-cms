<?php

namespace Duo\SecurityBundle\Controller;

use Duo\AdminBundle\Controller\AbstractCreateController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/security/role", name="duo_security_listing_role_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_SUPER_ADMIN')")
 */
class RoleCreateController extends AbstractCreateController
{
	use RoleConfigurationTrait;

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