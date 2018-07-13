<?php

namespace Duo\PageBundle\Twig;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duo\PageBundle\Entity\Page;

class PageTwigExtension extends \Twig_Extension
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * PageTwigExtension constructor
	 *
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFunctions()
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
	 * @return Page
	 */
	public function getPage(int $id, string $locale = null): ?Page
	{
		return $this->entityManager->getRepository(Page::class)->findById($id, $locale);
	}

	/**
	 * Get page by url
	 *
	 * @param string $url
	 * @param string $locale [optional]
	 *
	 * @return Page
	 */
	public function getPageByUrl(string $url, string $locale = null): ?Page
	{
		return $this->entityManager->getRepository(Page::class)->findOneByUrl($url, $locale);
	}

	/**
	 * Get page by name
	 *
	 * @param string $name
	 * @param string $locale [optional]
	 *
	 * @return Page
	 */
	public function getPageByName(string $name, string $locale = null): ?Page
	{
		return $this->entityManager->getRepository(Page::class)->findOneByName($name, $locale);
	}
}