<?php

namespace Duo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\AdminBundle\Entity\Behavior\ViewInterface;
use Duo\BehaviorBundle\Entity\PublishInterface;
use Duo\BehaviorBundle\Entity\PublishTrait;
use Duo\BehaviorBundle\Entity\TranslationInterface;
use Duo\BehaviorBundle\Entity\TreeInterface;
use Duo\BehaviorBundle\Entity\TreeTrait;

/**
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="Duo\AdminBundle\Repository\PageRepository")
 */
class Page extends AbstractNode implements TreeInterface, PublishInterface, ViewInterface
{
	use TreeTrait;
    use PublishTrait;

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