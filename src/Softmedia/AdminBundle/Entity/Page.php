<?php

namespace Softmedia\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softmedia\AdminBundle\Entity\Behavior\TranslationInterface;

/**
 * @ORM\Table(name="page")
 * @ORM\Entity()
 */
class Page extends AbstractNode
{
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
}