<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
	/**
	 * @Route("/")
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
    public function indexAction(Request $request)
    {
		return $this->json('AppBundle');
    }
}