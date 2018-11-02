<?php

namespace Duo\PageBundle\Entity\PagePart;

use Duo\PartBundle\Entity\PartInterface;

interface PagePartInterface extends PartInterface
{
	/**
	 * Get view
	 *
	 * @return string
	 */
	public function getView(): ?string;
}