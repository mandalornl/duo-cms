<?php

namespace Duo\PageBundle\Twig;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duo\PageBundle\Entity\PageInterface;

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
	 */
	public function getPage(int $id, string $locale = null): ?PageInterface
	{
		return $this->entityManager->getRepository(PageInterface::class)->findById($id, $locale);
	}

	/**
	 * Get page by url
	 *
	 * @param string $url
	 * @param string $locale [optional]
	 *
	 * @return PageInterface
	 */
	public function getPageByUrl(string $url, string $locale = null): ?PageInterface
	{
		return $this->entityManager->getRepository(PageInterface::class)->findOneByUrl($url, $locale);
	}

	/**
	 * Get page by name
	 *
	 * @param string $name
	 * @param string $locale [optional]
	 *
	 * @return PageInterface
	 */
	public function getPageByName(string $name, string $locale = null): ?PageInterface
	{
		return $this->entityManager->getRepository(PageInterface::class)->findOneByName($name, $locale);
	}
}