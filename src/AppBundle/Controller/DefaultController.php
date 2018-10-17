<?php

namespace AppBundle\Controller;

use Duo\PageBundle\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
	/**
	 * Index action
	 *
	 * @param Request $request
	 * @param PageRepository $pageRepository
	 * @param string $url [optional]
	 *
	 * @return Response
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

		return $this->render('@App/index.html.twig', [
			'page' => $page
		]);
	}
}