<?php

namespace Duo\SeoBundle\Entity\Property;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait SeoTrait
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="meta_title", type="string", length=60, nullable=true)
	 * @Assert\Length(max="60")
	 */
	protected $metaTitle;

	/**
	 * @var array
	 *
	 * @ORM\Column(name="meta_keywords", type="json", nullable=true)
	 */
	protected $metaKeywords;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="meta_description", type="text", nullable=true)
	 * @Assert\Length(max="300")
	 */
	protected $metaDescription;

	/**
	 * @var array
	 *
	 * @ORM\Column(name="meta_robots", type="json", length=56, nullable=true)
	 */
	protected $metaRobots;

	/**
	 * {@inheritDoc}
	 */
	public function setMetaTitle(?string $metaTitle): SeoInterface
	{
		$this->metaTitle = $metaTitle;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getMetaTitle(): ?string
	{
		return $this->metaTitle;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setMetaKeywords(?array $metaKeywords): SeoInterface
	{
		$this->metaKeywords = $metaKeywords;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getMetaKeywords(): ?array
	{
		return $this->metaKeywords;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setMetaDescription(?string $metaDescription): SeoInterface
	{
		$this->metaDescription = $metaDescription;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getMetaDescription(): ?string
	{
		return $this->metaDescription;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setMetaRobots(?array $metaRobots): SeoInterface
	{
		$this->metaRobots = $metaRobots;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getMetaRobots(): ?array
	{
		return $this->metaRobots;
	}
}
