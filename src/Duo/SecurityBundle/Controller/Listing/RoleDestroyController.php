<?php

namespace Duo\SecurityBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractDestroyController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/security/role", name="duo_security_listing_role_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_SUPER_ADMIN')")
 */
class RoleDestroyController extends AbstractDestroyController
{
	use RoleConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route(
	 *     path="/destroy/{id}.{_format}",
	 *     name="destroy",
	 *     requirements={ "id" = "\d+", "_format" = "html|json" },
	 *	   defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function destroyAction(Request $request, int $id = null): Response
	{
		return $this->doDestroyAction($request, $id);
	}
}