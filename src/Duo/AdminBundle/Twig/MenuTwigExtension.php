<?php

namespace Duo\AdminBundle\Twig;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duo\AdminBundle\Entity\Behavior\TreeInterface;
use Duo\AdminBundle\Service\Menu;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuTwigExtension extends \Twig_Extension
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * @var RequestStack
	 */
	private $requestStack;

	/**
	 * MenuTwigExtension constructor
	 *
	 * @param EntityManagerInterface $entityManager
	 * @param RequestStack $requestStack
	 */
	public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
	{
		$this->entityManager = $entityManager;
		$this->requestStack = $requestStack;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('get_menu', [$this, 'getMenu'])
		];
	}

	/**
	 * Get menu
	 *
	 * @param TreeInterface $root
	 *
	 * @return Menu
	 */
	public function getMenu(TreeInterface $root)
	{
		return (new Menu())
			->setRoot($root)
			->setEntityManager($this->entityManager)
			->setRequestStack($this->requestStack);
	}
}