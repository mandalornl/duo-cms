<?php

namespace Duo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\AdminBundle\Entity\Behavior\PublishInterface;
use Duo\AdminBundle\Entity\Behavior\PublishTrait;
use Duo\AdminBundle\Entity\Behavior\SlugInterface;
use Duo\AdminBundle\Entity\Behavior\SlugTrait;
use Duo\AdminBundle\Entity\Behavior\TranslationInterface;
use Duo\AdminBundle\Entity\Behavior\TreeInterface;
use Duo\AdminBundle\Entity\Behavior\TreeTrait;
use Duo\AdminBundle\Entity\Behavior\UrlInterface;
use Duo\AdminBundle\Entity\Behavior\UrlTrait;
use Duo\AdminBundle\Entity\Behavior\ViewInterface;

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