<?php

namespace Duo\SecurityBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractCreateController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/security/user", name="duo_security_listing_user_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class UserCreateController extends AbstractCreateController
{
	use UserConfigurationTrait;

	/**
	 * {@inheritDoc}
	 *
	 * @Route(
	 *     path="/create.{_format}",
	 *     name="create",
	 *     requirements={ "_format" = "html|json" },
	 *     defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function createAction(Request $request): Response
	{
		return $this->doCreateAction($request);
	}
}
