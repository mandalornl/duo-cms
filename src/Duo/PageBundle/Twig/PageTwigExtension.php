<?php

namespace Duo\PageBundle\Twig;

use Duo\PageBundle\Entity\PageInterface;
use Duo\PageBundle\Repository\PageRepository;

class PageTwigExtension extends \Twig_Extension
{
	/**
	 * @var PageRepository
	 */
	private $pageRepository;

	/**
	 * PageTwigExtension constructor
	 *
	 * @param PageRepository $pageRepository
	 */
	public function __construct(PageRepository $pageRepository)
	{
		$this->pageRepository = $pageRepository;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFunctions(): array
	{
		return [
			new \Twig_SimpleFunction('get_page', [$this, 'getPage']),
			new \Twig_SimpleFunction('get_page_by_url', [$this, 'getPageByUrl']),
			new \Twig_SimpleFunction('get_page_by_name', [$this, 'getPageByName'])
		];
	}

	/**
	 * Get page
	 *
	 * @param int $id
	 * @param string $locale [optional]
	 *
	 * @return PageInterface
	 *
	 * @throws \Throwable
	 */
	public function getPage(int $id, string $locale = null): ?PageInterface
	{
		return $this->pageRepository->findById($id, $locale);
	}

	/**
	 * Get page by url
	 *
	 * @param string $url
	 * @param string $locale [optional]
	 *
	 * @return PageInterface
	 *
	 * @throws \Throwable
	 */
	public function getPageByUrl(string $url, string $locale = null): ?PageInterface
	{
		return $this->pageRepository->findOneByUrl($url, $locale);
	}

	/**
	 * Get page by name
	 *
	 * @param string $name
	 * @param string $locale [optional]
	 *
	 * @return PageInterface
	 *
	 * @throws \Throwable
	 */
	public function getPageByName(string $name, string $locale = null): ?PageInterface
	{
		return $this->pageRepository->findOneByName($name, $locale);
	}
}