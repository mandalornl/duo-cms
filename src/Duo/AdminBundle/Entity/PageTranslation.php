<?php

namespace Duo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\AdminBundle\Entity\Behavior\SlugInterface;
use Duo\AdminBundle\Entity\Behavior\SlugTrait;
use Duo\AdminBundle\Entity\Behavior\UrlInterface;
use Duo\AdminBundle\Entity\Behavior\UrlTrait;

/**
 * @ORM\Table(name="page_translation")
 * @ORM\Entity()
 */
class PageTranslation extends AbstractNodeTranslation implements SlugInterface, UrlInterface
{
	use SlugTrait;
	use UrlTrait;

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
	 * @var string
	 *
	 * @ORM\Column(name="meta_title", type="string", nullable=true)
	 */
	protected $metaTitle;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="meta_keywords", type="string", nullable=true)
	 */
	protected $metaKeywords;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="meta_description", type="text", nullable=true)
	 */
	protected $metaDescription;

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