<?php

namespace Duo\PageBundle\Entity;

use Duo\CoreBundle\Entity\DuplicateInterface;
use Duo\CoreBundle\Entity\PreviewInterface;
use Duo\CoreBundle\Entity\Property\DeleteInterface;
use Duo\CoreBundle\Entity\Property\IdInterface;
use Duo\CoreBundle\Entity\Property\RevisionInterface;
use Duo\CoreBundle\Entity\Property\SortInterface;
use Duo\CoreBundle\Entity\Property\TimestampInterface;
use Duo\CoreBundle\Entity\Property\TranslateInterface;
use Duo\CoreBundle\Entity\Property\TreeInterface;
use Duo\CoreBundle\Entity\Property\VersionInterface;
use Duo\DraftBundle\Entity\Property\DraftInterface;
use Duo\TaxonomyBundle\Entity\Property\TaxonomyInterface;

interface PageInterface extends IdInterface,
								DuplicateInterface,
								TimestampInterface,
								TranslateInterface,
								VersionInterface,
								PreviewInterface,
								DeleteInterface,
								RevisionInterface,
								SortInterface,
								TreeInterface,
								TaxonomyInterface,
								DraftInterface
{
	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return PageInterface
	 */
	public function setName(?string $name): PageInterface;

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName(): ?string;
}