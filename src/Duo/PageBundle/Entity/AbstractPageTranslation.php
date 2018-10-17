<?php

namespace Duo\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\CloneTrait;
use Duo\CoreBundle\Entity\Property\PublishTrait;
use Duo\CoreBundle\Entity\Property\SlugTrait;
use Duo\CoreBundle\Entity\Property\TranslationTrait;
use Duo\CoreBundle\Entity\Property\UrlTrait;
use Duo\PartBundle\Entity\Property\PartTrait;
use Duo\SeoBundle\Entity\Property\SeoTrait;
use Symfony\Component\Validator\Constraints as Assert;

class AbstractPageTranslation implements PageTranslationInterface
{
	use CloneTrait;
	use TranslationTrait;
	use SlugTrait;
	use UrlTrait;
	use PublishTrait;
	use SeoTrait;
	use PartTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="title", type="string", nullable=true)
	 * @Assert\NotBlank()
	 */
	protected $title;

	/**
	 * @var bool
	 *
	 * @ORM\Column(name="visible_menu", type="boolean", options={ "default" = 1 })
	 */
	protected $visibleMenu = true;

	/**
	 * {@inheritdoc}
	 */
	public function setTitle(?string $title): PageTranslationInterface
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTitle(): ?string
	{
		return $this->title;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setVisibleMenu(bool $visibleMenu = true): PageTranslationInterface
	{
		$this->visibleMenu = $visibleMenu;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getVisibleMenu(): bool
	{
		return $this->visibleMenu;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getValueToSlugify(): string
	{
		return $this->title;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getValueToUrlize(): string
	{
		return $this->slug;
	}
}