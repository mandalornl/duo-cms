<?php

namespace Softmedia\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softmedia\AdminBundle\Entity\Behavior\SluggableInterface;
use Softmedia\AdminBundle\Entity\Behavior\SluggableTrait;
use Softmedia\AdminBundle\Entity\Behavior\TranslationInterface;
use Softmedia\AdminBundle\Entity\Behavior\TreeableInterface;
use Softmedia\AdminBundle\Entity\Behavior\TreeableTrait;
use Softmedia\AdminBundle\Entity\Behavior\UrlableInterface;
use Softmedia\AdminBundle\Entity\Behavior\UrlableTrait;

/**
 * @ORM\Table(name="page")
 * @ORM\Entity()
 */
class Page extends AbstractNode implements TreeableInterface, SluggableInterface, UrlableInterface
{
	use TreeableTrait;
    use SluggableTrait;
    use UrlableTrait;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="published", type="boolean", options={ "default" = 0 })
	 */
	protected $published = 0;

	/**
	 * Set published
	 *
	 * @param boolean $published
	 *
	 * @return Page
	 */
	public function setPublished(bool $published = false): Page
	{
		$this->published = $published;

		return $this;
	}

	/**
	 * Get published
	 *
	 * @return boolean
	 */
	public function getPublished(): bool
	{
		return $this->published;
	}

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