<?php

namespace Duo\FormBundle\Controller\Listing;

use Duo\coreBundle\Controller\Listing\AbstractDuplicateController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/form", name="duo_form_listing_form_")
 */
class FormDuplicateController extends AbstractDuplicateController
{
	use FormConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route(
	 *     path="/duplicate/{id}.{_format}",
	 *     name="duplicate",
	 *     requirements={ "id" = "\d+", "_format" = "html|json" },
	 *	   defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function duplicateAction(Request $request, int $id): Response
	{
		return $this->doDuplicateAction($request, $id);
	}
}