<?php

namespace Duo\AdminBundle\Controller;

use Duo\PageBundle\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UrlController extends Controller
{
	/**
	 * Index action
	 *
	 * @param Request $request
	 * @param PageRepository $pageRepository
	 * @param string $url [optional]
	 *
	 * @return JsonResponse
	 *
	 * @throws \Throwable
	 */
	public function indexAction(Request $request, PageRepository $pageRepository, string $url = ''): Response
	{
		$page = $pageRepository->findOneByUrl($url, $request->getLocale());

		if ($page === null)
		{
			throw $this->createNotFoundException("Page for '/{$url}' not found");
		}

		return $this->json([
			'url' => "/{$url}",
			'locale' => $request->getLocale(),
			'page' => $page->translate($request->getLocale())->getTitle()
		]);
	}
}