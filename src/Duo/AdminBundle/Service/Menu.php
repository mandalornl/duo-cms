<?php

namespace Duo\AdminBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duo\BehaviorBundle\Entity\TreeInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Menu
{
	/**
	 * @var TreeInterface
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
	 * @param TreeInterface $root
	 *
	 * @return Menu
	 */
	public function setRoot(TreeInterface $root): Menu
	{
		$this->root = $root;

		return $this;
	}

	/**
	 * Get root
	 *
	 * @return TreeInterface
	 */
	public function getRoot(): ?TreeInterface
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