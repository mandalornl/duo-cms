<?php

namespace Duo\PartBundle\Controller;

use Duo\PartBundle\Form\PartCollectionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="duo_part_modal_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class ModalController extends Controller
{
	/**
	 * View prototypes
	 *
	 * @Route("/prototypes", name="prototypes")
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function viewPrototypes(Request $request)
	{
		$form = $this->createForm(PartCollectionType::class);

		return $this->json([
			'result' => $this->renderView('@DuoPart/Modal/prototypes.html.twig', [
				'form' => $form->createView()
			])
		]);
	}
}