<?php

namespace Duo\SecurityBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractUpdateController;
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
	 * @Route(
	 *     path="/{id}.{_format}",
	 *     name="update",
	 *     requirements={ "id" = "\d+", "_format" = "html|json" },
	 *	   defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function updateAction(Request $request, int $id): Response
	{
		return $this->doUpdateAction($request, $id);
	}
}