<?php

namespace Softmedia\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softmedia\AdminBundle\Entity\Behavior\PublishableInterface;
use Softmedia\AdminBundle\Entity\Behavior\PublishableTrait;
use Softmedia\AdminBundle\Entity\Behavior\SluggableInterface;
use Softmedia\AdminBundle\Entity\Behavior\SluggableTrait;
use Softmedia\AdminBundle\Entity\Behavior\TranslationInterface;
use Softmedia\AdminBundle\Entity\Behavior\TreeableInterface;
use Softmedia\AdminBundle\Entity\Behavior\TreeableTrait;
use Softmedia\AdminBundle\Entity\Behavior\UrlableInterface;
use Softmedia\AdminBundle\Entity\Behavior\UrlableTrait;
use Softmedia\AdminBundle\Entity\Behavior\ViewableInterface;

/**
 * @ORM\Table(name="page")
 * @ORM\Entity()
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