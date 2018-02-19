<?php

namespace Duo\SeoBundle\Controller;

use Duo\SeoBundle\Entity\Robot;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="duo_seo_robot_")
 */
class RobotController extends Controller
{
	/**
	 * Index
	 *
	 * @Route("/robots.txt", name="index")
	 * @Method("GET")
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

		$content = $entity->getContent();
		$content = str_replace('{scheme}', $request->getScheme(), $content);
		$content = str_replace('{host}', $request->getHttpHost(), $content);

		return new Response($content, 200, [
			'content-type' => 'text/plain'
		]);
	}
}