<?php

namespace Duo\SeoBundle\Controller;

use Duo\SeoBundle\Entity\Robot;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="duo_seo_robots_")
 */
class RobotController extends Controller
{
	/**
	 * Index
	 *
	 * @Route("/robots.txt", name="index", methods={ "GET" })
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function indexAction(Request $request): Response
	{
		$entity = $this->getDoctrine()->getRepository(Robot::class)->findLatest();

		if ($entity === null)
		{
			throw $this->createNotFoundException();
		}

		$content = strtr($entity->getContent(), [
			'{scheme}' => $request->getScheme(),
			'{host}' => $request->getHttpHost()
		]);

		return new Response($content, 200, [
			'content-type' => 'text/plain'
		]);
	}
}