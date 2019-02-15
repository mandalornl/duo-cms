<?php

namespace Duo\SeoBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractController;
use Duo\SeoBundle\Entity\Robot;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/seo/robots", name="duo_seo_listing_robots_")
 */
class RobotIndexController extends AbstractController
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

			// reply with json response
			if ($request->getRequestFormat() === 'json')
			{
				return $this->json([
					'success' => true,
					'message' => $this->get('translator')->trans('duo_admin.save_success', [], 'flashes')
				]);
			}

			$this->addFlash('success', $this->get('translator')->trans('duo_admin.save_success', [], 'flashes'));

			return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
		}

		return $this->render('@DuoSeo/Listing/robots.html.twig', (array)$this->createTwigContext([
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

		$content = <<<EOT
User-agent: *
Disallow: /admin/
Sitemap: {scheme}://{host}/sitemap.xml
EOT;

		return (new Robot())
			->setContent($content);
	}
}
