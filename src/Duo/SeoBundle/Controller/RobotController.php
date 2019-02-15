<?php

namespace Duo\SeoBundle\Controller;

use Duo\SeoBundle\Entity\Robot;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RobotController extends AbstractController
{
	/**
	 * Index
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
