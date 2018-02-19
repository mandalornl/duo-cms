<?php

namespace Duo\FormBundle\Controller;

use Duo\AdminBundle\Controller\AbstractDestroyController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/form-result", name="duo_form_listing_result_")
 */
class FormResultDestroyController extends AbstractDestroyController
{
	use FormResultConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/destroy/{id}", name="destroy", requirements={ "id" = "\d+" })
	 * @Method({"GET", "POST"})
	 */
	public function destroyAction(Request $request, int $id = null)
	{
		return $this->doDestroyAction($request, $id);
	}
}