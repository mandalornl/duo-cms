<?php

namespace Duo\SeoBundle\Controller;

use Duo\PageBundle\Entity\Page;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends Controller
{
	/**
	 * Index
	 *
	 * @Route("/sitemap.xml")
	 * @Method("GET")
	 *
	 * @param Request $request
	 *
	 * @return Response
	 *
	 * @throws \Throwable
	 */
	public function indexAction(Request $request): Response
	{
		$view = $this->renderView('@DuoSeo/sitemap_index.xml.twig', [
			'lastMod' => $this->getDoctrine()->getRepository(Page::class)->findLastModifiedAt(),
			'locales' => $this->get('duo.admin.locale_helper')->getLocales()
		]);

		return new Response($view, 200, [
			'content-type' => 'application/xml'
		]);
	}

	/**
	 * Sitemap
	 *
	 * @Route("/sitemap-{locale}.xml", requirements={ "locale" = "%locales%" })
	 *
	 * @param Request $request
	 * @param string $locale
	 *
	 * @return Response
	 *
	 * @throws \Throwable
	 */
	public function sitemapAction(Request $request, string $locale): Response
	{
		$request->setLocale($locale);

		$view = $this->renderView('@DuoSeo/sitemap.xml.twig', [
			'root' => $this->getDoctrine()->getRepository(Page::class)->findOneByName('home', $locale)
		]);

		return new Response($view, 200, [
			'content-type' => 'application/xml'
		]);
	}
}