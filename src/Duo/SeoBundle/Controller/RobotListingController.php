<?php

namespace Duo\SeoBundle\Controller;

use Doctrine\Common\Annotations\AnnotationException;
use Duo\AdminBundle\Controller\RoutePrefixTrait;
use Duo\SeoBundle\Entity\Robot;
use Duo\SeoBundle\Form\RobotListingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(name="duo_seo_listing_robot_")
 *
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED') and has_role('ROLE_ADMIN')")
 */
class RobotListingController extends Controller
{
	use RoutePrefixTrait;

	/**
	 * Index action
	 *
	 * @Route("/", name="index")
	 * @Method({"GET", "POST"})
	 *
	 * @param Request $request
	 *
	 * @return Response|RedirectResponse
	 *
	 * @throws AnnotationException
	 */
	public function indexAction(Request $request)
	{
		$entity = $this->getEntity();

		$form = $this->createForm(RobotListingType::class, $entity, [
			'attr' => [
				'class' => 'form-edit'
			]
		]);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this->redirectToRoute("{$this->getRoutePrefix()}_index");
		}

		return $this->render('@DuoSeo/Listing/robots.html.twig', [
			'menu' => $this->get('duo.admin.menu_builder')->createView(),
			'form' => $form->createView(),
			'routePrefix' => $this->getRoutePrefix()
		]);
	}

	/**
	 * Get entity
	 *
	 * @return Robot
	 */
	private function getEntity(): Robot
	{
		$entity = $this->getDoctrine()->getRepository(Robot::class)->findLatest();
		if ($entity)
		{
			return $entity;
		}

		$content = <<<EOD
User-agent: *
Disallow: /admin/
Sitemap: {protocol}://{host}/sitemap.xml
EOD;

		return (new Robot())
			->setContent($content);
	}
}