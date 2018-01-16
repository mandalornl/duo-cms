<?php

namespace Duo\NodeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\PublishInterface;
use Duo\BehaviorBundle\Entity\PublishTrait;
use Duo\BehaviorBundle\Entity\SlugInterface;
use Duo\BehaviorBundle\Entity\SlugTrait;
use Duo\BehaviorBundle\Entity\UrlInterface;
use Duo\BehaviorBundle\Entity\UrlTrait;
use Duo\PagePartBundle\Entity\NodePagePartInterface;
use Duo\PagePartBundle\Entity\NodePagePartTrait;
use Duo\SeoBundle\Entity\SeoInterface;
use Duo\SeoBundle\Entity\SeoTrait;

/**
 * @ORM\Table(name="page_translation")
 * @ORM\Entity()
 */
class PageTranslation extends AbstractNodeTranslation implements SlugInterface, UrlInterface, PublishInterface, SeoInterface, NodePagePartInterface
{
	use SlugTrait;
	use UrlTrait;
	use PublishTrait;
	use SeoTrait;
	use NodePagePartTrait;

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