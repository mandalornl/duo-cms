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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(fields={ "url", "locale" }, message="duo_page.errors.url_used")
 */
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
	 * {@inheritDoc}
	 */
	public function setTitle(?string $title): PageTranslationInterface
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTitle(): ?string
	{
		return $this->title;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getValueToSlugify(): string
	{
		return $this->title;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getValueToUrlize(): string
	{
		return $this->slug;
	}

	/**
	 * On clone slug
	 */
	protected function onCloneSlug(): void
	{
		// ensure unique slug, which in turn results in a unique url
		$this->slug = ltrim(uniqid("{$this->slug}-"), '-');
	}
}
