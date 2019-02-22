<?php

namespace AppBundle\Controller;

use Duo\PageBundle\Entity\PageInterface;
use Duo\PageBundle\Entity\PageTranslationInterface;
use Duo\PageBundle\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
	/**
	 * Index action
	 *
	 * @param Request $request
	 * @param string $url [optional]
	 *
	 * @return Response
	 *
	 * @throws \Throwable
	 */
	public function indexAction(Request $request, string $url = ''): Response
	{
		/**
		 * @var PageRepository $repository
		 */
		$repository = $this->getDoctrine()->getRepository(PageInterface::class);

		$page = $repository->findOneByUrl($url, $request->getLocale());

		if ($page === null)
		{
			throw $this->createNotFoundException("Page '/{$url}' not found");
		}

		/**
		 * @var PageTranslationInterface $translation
		 */
		$translation = $page->translate($request->getLocale());

		return $this->render('@App/index.html.twig', [
			'page' => $page,
			'pageTranslation' => $translation
		]);
	}
}
