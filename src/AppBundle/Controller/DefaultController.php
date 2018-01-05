<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="app_")
 */
class DefaultController extends Controller
{
	/**
	 * Index action
	 *
	 * @Route("/", name="index")
	 * @Method("GET")
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
    public function indexAction(Request $request): JsonResponse
    {
    	return $this->json([
    		'bundle' => 'AppBundle'
		]);
    }
}