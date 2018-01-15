<?php

namespace Duo\PagePartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="duo_pagepart_")
 */
class DefaultController extends Controller
{
	/**
	 * @Route("/", name="index")
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function indexAction(Request $request)
	{
		return $this->json('PagePartBundle');
	}
}