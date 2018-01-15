<?php

namespace Duo\PagePartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait PagePartTrait
{
	/**
	 * @var Collection
	 */
	protected $pageParts;

	/**
	 * {@inheritdoc}
	 */
	public function addPagePart(PagePartInterface $pagePart): NodePagePartInterface
	{
		$this->getPageParts()->add($pagePart);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removePagePart(PagePartInterface $pagePart): NodePagePartInterface
	{
		$this->getPageParts()->removeElement($pagePart);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPageParts()
	{
		return $this->pageParts = $this->pageParts ?: new ArrayCollection();
	}

	/**
	 * On clone page parts
	 */
	protected function onClonePageParts(): void
	{
		$pageParts = $this->getPageParts();
		$this->pageParts = new ArrayCollection();

		foreach ($pageParts as $weight => $pagePart)
		{
			$this->pageParts->set($weight, clone $pagePart);
		}
	}
}