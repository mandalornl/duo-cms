<?php

namespace Duo\PageBundle\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Duo\PageBundle\Entity\PageInterface;
use Duo\PageBundle\Repository\PageRepository;

class PageTwigExtension extends \Twig_Extension
{
	/**
	 * @var EntityManagerInterface
	 */
	private $manager;

	/**
	 * PageTwigExtension constructor
	 *
	 * @param EntityManagerInterface $manager
	 */
	public function __construct(EntityManagerInterface $manager)
	{
		$this->manager = $manager;
	}

	/**
	 * {@inheritDoc}
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
	 */
	public function getPage(int $id, string $locale = null): ?PageInterface
	{
		return $this->getRepository()->findOneById($id, $locale);
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
		return $this->getRepository()->findOneByUrl($url, $locale);
	}

	/**
	 * Get page by name
	 *
	 * @param string $name
	 * @param string $locale [optional]
	 *
	 * @return PageInterface
	 *
	 */
	public function getPageByName(string $name, string $locale = null): ?PageInterface
	{
		return $this->getRepository()->findOneByName($name, $locale);
	}

	/**
	 * Get repository
	 *
	 * @return PageRepository
	 */
	private function getRepository(): PageRepository
	{
		/**
		 * @var PageRepository $repository
		 */
		$repository = $this->manager->getRepository(PageInterface::class);

		return $repository;
	}
}
