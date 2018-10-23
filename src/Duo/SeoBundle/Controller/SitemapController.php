<?php

namespace Duo\SeoBundle\Controller;

use Duo\AdminBundle\Helper\LocaleHelper;
use Duo\PageBundle\Repository\PageRepository;
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
	 * @param PageRepository $repository
	 * @param LocaleHelper $localeHelper
	 *
	 * @return Response
	 */
	public function indexAction(PageRepository $repository, LocaleHelper $localeHelper): Response
	{
		$view = $this->renderView('@DuoSeo/sitemap_index.xml.twig', [
			'lastMod' => $repository->findLastModifiedAt(),
			'locales' => $localeHelper->getLocales()
		]);

		return new Response($view, 200, [
			'content-type' => 'application/xml'
		]);
	}

	/**
	 * Feed
	 *
	 * @Route("/sitemap-{locale}.xml", name="feed", requirements={ "locale" = "%locales%" }, methods={ "GET" })
	 *
	 * @param Request $request
	 * @param PageRepository $repository
	 * @param string $locale
	 *
	 * @return Response
	 *
	 */
	public function feedAction(Request $request, PageRepository $repository, string $locale): Response
	{
		$request->setLocale($locale);

		$view = $this->renderView('@DuoSeo/sitemap.xml.twig', [
			'root' => $repository->findOneByName('home', $locale)
		]);

		return new Response($view, 200, [
			'content-type' => 'application/xml'
		]);
	}
}