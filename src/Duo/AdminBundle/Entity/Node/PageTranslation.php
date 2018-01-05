<?php

namespace Duo\AdminBundle\Entity\Node;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\PublishInterface;
use Duo\BehaviorBundle\Entity\PublishTrait;
use Duo\BehaviorBundle\Entity\SlugInterface;
use Duo\BehaviorBundle\Entity\SlugTrait;
use Duo\BehaviorBundle\Entity\UrlInterface;
use Duo\BehaviorBundle\Entity\UrlTrait;

/**
 * @ORM\Table(name="page_translation")
 * @ORM\Entity()
 */
class PageTranslation extends AbstractNodeTranslation implements SlugInterface, UrlInterface, PublishInterface
{
	use SlugTrait;
	use UrlTrait;
	use PublishTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="title", type="string", nullable=true)
	 */
	protected $title;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="content", type="text", nullable=true)
	 */
	protected $content;

	/**
	 * @var bool
	 *
	 * @ORM\Column(name="visible", type="boolean", options={ "default" = 1 })
	 */
	protected $visible = true;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="meta_title", type="string", length=55, nullable=true)
	 */
	protected $metaTitle;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="meta_keywords", type="string", length=155, nullable=true)
	 */
	protected $metaKeywords;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="meta_description", type="text", nullable=true)
	 */
	protected $metaDescription;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="meta_robots", type="string", length=56, nullable=true)
	 */
	protected $metaRobots;

	/**
	 * Set title
	 *
	 * @param string $title
	 *
	 * @return PageTranslation
	 */
	public function setTitle(string $title = null): PageTranslation
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function getTitle(): ?string
	{
		return $this->title;
	}

	/**
	 * Set content
	 *
	 * @param string $content
	 *
	 * @return PageTranslation
	 */
	public function setContent(string $content = null): PageTranslation
	{
		$this->content = $content;

		return $this;
	}

	/**
	 * Get content
	 *
	 * @return string
	 */
	public function getContent(): ?string
	{
		return $this->content;
	}

	/**
	 * Set visible
	 *
	 * @param bool $visible
	 *
	 * @return PageTranslation
	 */
	public function setVisible(bool $visible = true): PageTranslation
	{
		$this->visible = $visible;

		return $this;
	}

	/**
	 * Get visible
	 *
	 * @return bool
	 */
	public function getVisible(): bool
	{
		return $this->visible;
	}

	/**
	 * Set metaTitle
	 *
	 * @param string $metaTitle
	 *
	 * @return PageTranslation
	 */
	public function setMetaTitle(string $metaTitle = null): PageTranslation
	{
		$this->metaTitle = $metaTitle;

		return $this;
	}

	/**
	 * Get metaTitle
	 *
	 * @return string
	 */
	public function getMetaTitle(): ?string
	{
		return $this->metaTitle;
	}

	/**
	 * Set metaKeywords
	 *
	 * @param string $metaKeywords
	 *
	 * @return PageTranslation
	 */
	public function setMetaKeywords(string $metaKeywords = null): PageTranslation
	{
		$this->metaKeywords = $metaKeywords;

		return $this;
	}

	/**
	 * Get metaKeywords
	 *
	 * @return string
	 */
	public function getMetaKeywords(): ?string
	{
		return $this->metaKeywords;
	}

	/**
	 * Set metaDescription
	 *
	 * @param string $metaDescription
	 *
	 * @return PageTranslation
	 */
	public function setMetaDescription(string $metaDescription = null): PageTranslation
	{
		$this->metaDescription = $metaDescription;

		return $this;
	}

	/**
	 * Get metaDescription
	 *
	 * @return string
	 */
	public function getMetaDescription(): ?string
	{
		return $this->metaDescription;
	}

	/**
	 * Set metaRobots
	 *
	 * @param string $metaRobots
	 *
	 * @return PageTranslation
	 */
	public function setMetaRobots(string $metaRobots = null): PageTranslation
	{
		$this->metaRobots = $metaRobots;

		return $this;
	}

	/**
	 * Get metaRobots
	 *
	 * @return string
	 */
	public function getMetaRobots(): ?string
	{
		return $this->metaRobots;
	}

	/**
	 * Get value to slugify
	 *
	 * @return string
	 */
	public function getValueToSlugify(): string
	{
		return $this->title;
	}

	/**
	 * Get value to urlize
	 *
	 * @return string
	 */
	public function getValueToUrlize(): string
	{
		return $this->slug;
	}
}