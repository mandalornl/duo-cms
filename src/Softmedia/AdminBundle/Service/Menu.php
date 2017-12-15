<?php

namespace Softmedia\AdminBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Softmedia\AdminBundle\Entity\Behavior\TreeableInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Menu
{
	/**
	 * @var TreeableInterface
	 */
	private $root;

	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * @var RequestStack
	 */
	private $requestStack;

	/**
	 * Set root
	 *
	 * @param TreeableInterface $root
	 *
	 * @return Menu
	 */
	public function setRoot(TreeableInterface $root): Menu
	{
		$this->root = $root;

		return $this;
	}

	/**
	 * Get root
	 *
	 * @return TreeableInterface
	 */
	public function getRoot(): ?TreeableInterface
	{
		return $this->root;
	}

	/**
	 * Set entityManager
	 *
	 * @param EntityManagerInterface $entityManager
	 *
	 * @return Menu
	 */
	public function setEntityManager(EntityManagerInterface $entityManager): Menu
	{
		$this->entityManager = $entityManager;

		return $this;
	}

	/**
	 * Set requestStack
	 *
	 * @param RequestStack $requestStack
	 *
	 * @return Menu
	 */
	public function setRequestStack(RequestStack $requestStack): Menu
	{
		$this->requestStack = $requestStack;

		return $this;
	}
}