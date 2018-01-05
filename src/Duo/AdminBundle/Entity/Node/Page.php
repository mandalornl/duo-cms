<?php

namespace Duo\AdminBundle\Entity\Node;

use Doctrine\ORM\Mapping as ORM;
use Duo\AdminBundle\Entity\Behavior\ViewInterface;
use Duo\BehaviorBundle\Entity\TranslationInterface;
use Duo\BehaviorBundle\Entity\TreeInterface;
use Duo\BehaviorBundle\Entity\TreeTrait;

/**
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="Duo\AdminBundle\Repository\Node\PageRepository")
 */
class Page extends AbstractNode implements TreeInterface, ViewInterface
{
	use TreeTrait;

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