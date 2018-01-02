<?php

namespace Duo\AdminBundle\Controller;

use Duo\AdminBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UrlController extends Controller
{
	/**
	 * Index action
	 *
	 * @param Request $request
	 * @param string $url
	 *
	 * @return JsonResponse
	 */
	public function indexAction(Request $request, string $url)
	{
		$page = $this->getDoctrine()->getRepository(Page::class)->findOneByUrl($url, $request->getLocale());
		if ($page === null)
		{
			throw $this->createNotFoundException("Page with url '{$url}' not found");
		}

		return $this->json([
			'url' => $url,
			'locale' => $request->getLocale()
		]);
	}
}