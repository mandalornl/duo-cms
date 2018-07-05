<?php

namespace Duo\SecurityBundle\Controller;

use Duo\AdminBundle\Controller\AbstractUpdateController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/security/group", name="duo_security_listing_group_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_SUPER_ADMIN')")
 */
class GroupUpdateController extends AbstractUpdateController
{
	use GroupConfigurationTrait;

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