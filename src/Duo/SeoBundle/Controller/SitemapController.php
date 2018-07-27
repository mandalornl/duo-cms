<?php

namespace Duo\SeoBundle\Controller;

use Duo\AdminBundle\Helper\LocaleHelper;
use Duo\PageBundle\Entity\PageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="duo_seo_sitemap_")
 */
class SitemapController extends Controller
{
	/**
	 * Index
	 *
	 * @Route("/sitemap.xml", name="index", methods={ "GET" })
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
			'lastMod' => $this->getDoctrine()->getRepository(PageInterface::class)->findLastModifiedAt(),
			'locales' => $this->get(LocaleHelper::class)->getLocales()
		]);

		return new Response($view, 200, [
			'content-type' => 'application/xml'
		]);
	}

	/**
	 * Feed
	 *
	 * @Route("/sitemap-{locale}.xml", requirements={ "locale" = "%locales%" }, name="feed")
	 *
	 * @param Request $request
	 * @param string $locale
	 *
	 * @return Response
	 *
	 * @throws \Throwable
	 */
	public function feedAction(Request $request, string $locale): Response
	{
		$request->setLocale($locale);

		$view = $this->renderView('@DuoSeo/sitemap.xml.twig', [
			'root' => $this->getDoctrine()->getRepository(PageInterface::class)->findOneByName('home', $locale)
		]);

		return new Response($view, 200, [
			'content-type' => 'application/xml'
		]);
	}
}