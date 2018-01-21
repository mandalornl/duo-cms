<?php

namespace Duo\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\DeleteInterface;
use Duo\BehaviorBundle\Entity\DeleteTrait;
use Duo\BehaviorBundle\Entity\RevisionInterface;
use Duo\BehaviorBundle\Entity\RevisionTrait;
use Duo\BehaviorBundle\Entity\SortInterface;
use Duo\BehaviorBundle\Entity\SortTrait;
use Duo\BehaviorBundle\Entity\TranslationInterface;
use Duo\BehaviorBundle\Entity\TreeInterface;
use Duo\BehaviorBundle\Entity\TreeTrait;
use Duo\NodeBundle\Entity\AbstractNode;
use Duo\TaxonomyBundle\Entity\TaxonomyInterface;
use Duo\TaxonomyBundle\Entity\TaxonomyTrait;

/**
 * @ORM\Table(name="page")
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