<?php

namespace Duo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\AdminBundle\Entity\Behavior\PublishableInterface;
use Duo\AdminBundle\Entity\Behavior\PublishableTrait;
use Duo\AdminBundle\Entity\Behavior\SluggableInterface;
use Duo\AdminBundle\Entity\Behavior\SluggableTrait;
use Duo\AdminBundle\Entity\Behavior\TranslationInterface;
use Duo\AdminBundle\Entity\Behavior\TreeableInterface;
use Duo\AdminBundle\Entity\Behavior\TreeableTrait;
use Duo\AdminBundle\Entity\Behavior\UrlableInterface;
use Duo\AdminBundle\Entity\Behavior\UrlableTrait;
use Duo\AdminBundle\Entity\Behavior\ViewableInterface;

/**
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="Duo\AdminBundle\Repository\PageRepository")
 */
class Page extends AbstractNode implements TreeableInterface, SluggableInterface, UrlableInterface, PublishableInterface, ViewableInterface
{
	use TreeableTrait;
    use SluggableTrait;
    use UrlableTrait;
    use PublishableTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @return PageTranslation|TranslationInterface
	 */
	public function translate(string $locale = null, bool $fallback = true): PageTranslation
	{
		return $this->doTranslate($locale, $fallback);
	}

    /**
     * {@inheritdoc}
     */
    public function getValueToSlugify(): string
    {
        return $this->name;
    }

	/**
	 * {@inheritdoc}
	 */
	public function getValueToUrlize(): string
	{
		return $this->slug;
	}
}