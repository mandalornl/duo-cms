<?php

namespace Duo\PagePartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

interface NodePagePartInterface
{
	/**
	 * Add pagePart
	 *
	 * @param PagePartInterface $pagePart
	 *
	 * @return NodePagePartInterface
	 */
	public function addPagePart(PagePartInterface $pagePart): NodePagePartInterface;

	/**
	 * Remove pagePart
	 *
	 * @param PagePartInterface $pagePart
	 *
	 * @return NodePagePartInterface
	 */
	public function removePagePart(PagePartInterface $pagePart): NodePagePartInterface;

	/**
	 * Get pageParts
	 *
	 * @return ArrayCollection
	 */
	public function getPageParts();
}