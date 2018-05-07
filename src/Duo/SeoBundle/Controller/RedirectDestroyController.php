<?php

namespace Duo\SeoBundle\Controller;

use Duo\AdminBundle\Controller\AbstractDestroyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/seo/redirect", name="duo_seo_listing_redirect_")
 */
class RedirectDestroyController extends AbstractDestroyController
{
	use RedirectConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/destroy/{id}", name="destroy", requirements={ "id" = "\d+" }, methods={ "GET", "POST" })
	 */
	public function destroyAction(Request $request, int $id = null): Response
	{
		return $this->doDestroyAction($request, $id);
	}
}