<?php

namespace Duo\SeoBundle\Controller;

use Duo\AdminBundle\Controller\AbstractController;
use Duo\SeoBundle\Entity\Robot;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/seo/robots", name="duo_seo_listing_robot_")
 */
class RobotListController extends AbstractController
{
	use RobotConfigurationTrait;

	/**
	 * Index action
	 *
	 * @Route("/", name="index", methods={ "GET", "POST" })
	 *
	 * @param Request $request
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws \Throwable
	 */
	public function indexAction(Request $request): Response
	{
		$entity = $this->getEntity();

		$form = $this->createForm($this->getFormType(), $entity);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$manager = $this->getDoctrine()->getManager();
			$manager->persist($entity);
			$manager->flush();

			return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
		}

		return $this->render('@DuoSeo/Listing/robots.html.twig', (array)$this->getDefaultContext([
			'form' => $form->createView()
		]));
	}

	/**
	 * Get entity
	 *
	 * @return Robot
	 */
	private function getEntity(): Robot
	{
		$entity = $this->getDoctrine()->getRepository(Robot::class)->findLatest();

		if ($entity !== null)
		{
			return $entity;
		}

		$content = <<<EOD
User-agent: *
Disallow: /admin/
Sitemap: {scheme}://{host}/sitemap.xml
EOD;

		return (new Robot())
			->setContent($content);
	}
}