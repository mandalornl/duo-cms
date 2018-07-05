<?php

namespace Duo\SeoBundle\Controller;

use Duo\AdminBundle\Controller\AbstractCreateController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/seo/redirect", name="duo_seo_listing_redirect_")
 */
class RedirectCreateController extends AbstractCreateController
{
	use RedirectConfigurationTrait;

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