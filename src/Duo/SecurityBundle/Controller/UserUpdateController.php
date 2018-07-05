<?php

namespace Duo\SecurityBundle\Controller;

use Duo\AdminBundle\Controller\AbstractUpdateController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/security/user", name="duo_security_listing_user_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_SUPER_ADMIN')")
 */
class UserUpdateController extends AbstractUpdateController
{
	use UserConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/{id}", name="update", requirements={ "id" = "\d+" }, methods={ "GET", "POST" })
	 */
	public function updateAction(Request $request, int $id): Response
	{
		return $this->doUpdateAction($request, $id);
	}
}