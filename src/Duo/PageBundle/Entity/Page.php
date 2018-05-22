<?php

namespace Duo\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\DeleteInterface;
use Duo\CoreBundle\Entity\DeleteTrait;
use Duo\CoreBundle\Entity\RevisionInterface;
use Duo\CoreBundle\Entity\RevisionTrait;
use Duo\CoreBundle\Entity\SortInterface;
use Duo\CoreBundle\Entity\SortTrait;
use Duo\CoreBundle\Entity\TranslationInterface;
use Duo\CoreBundle\Entity\TreeInterface;
use Duo\CoreBundle\Entity\TreeTrait;
use Duo\NodeBundle\Entity\AbstractNode;
use Duo\TaxonomyBundle\Entity\TaxonomyInterface;
use Duo\TaxonomyBundle\Entity\TaxonomyTrait;

/**
 * @ORM\Table(name="duo_page")
 * @ORM\Entity(repositoryClass="Duo\PageBundle\Repository\PageRepository")
 */
class Page extends AbstractNode implements DeleteInterface,
										   RevisionInterface,
										   SortInterface,
										   TreeInterface,
										   ViewInterface,
										   TaxonomyInterface
{
	use DeleteTrait;
	use RevisionTrait;
	use SortTrait;
	use TreeTrait;
	use TaxonomyTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @return PageTranslation|TranslationInterface
	 */
	public function translate(string $locale = null, bool $fallback = true): PageTranslation
	{
		return $this->doTranslate($locale, $fallback);
	}
}