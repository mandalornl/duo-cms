<?php

namespace Duo\SeoBundle\Controller;

use Duo\AdminBundle\Helper\LocaleHelper;
use Duo\PageBundle\Entity\PageInterface;
use Duo\PageBundle\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends AbstractController
{
	/**
	 * @var LocaleHelper
	 */
	private $localeHelper;

	/**
	 * SitemapController constructor
	 *
	 * @param LocaleHelper $localeHelper
	 */
	public function __construct(LocaleHelper $localeHelper)
	{
		$this->localeHelper = $localeHelper;
	}

	/**
	 * Index action
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
			'lastMod' => $this->getPageRepository()->findLastModifiedAt(),
			'locales' => $this->localeHelper->getLocales()
		]);

		return new Response($view, 200, [
			'content-type' => 'application/xml'
		]);
	}

	/**
	 * Feed
	 *
	 * @param Request $request
	 *
	 * @return Response
	 *
	 */
	public function feedAction(Request $request): Response
	{
		$view = $this->renderView('@DuoSeo/sitemap.xml.twig', [
			'root' => $this->getPageRepository()->findOneByName('home', $request->getLocale())
		]);

		return new Response($view, 200, [
			'content-type' => 'application/xml'
		]);
	}

	/**
	 * Get page repository
	 *
	 * @return PageRepository
	 */
	private function getPageRepository(): PageRepository
	{
		/**
		 * @var PageRepository $repository
		 */
		$repository = $this->getDoctrine()->getRepository(PageInterface::class);

		return $repository;
	}
}
