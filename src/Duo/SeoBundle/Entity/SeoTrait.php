<?php

namespace Duo\SeoBundle\Entity;

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
	 * @var string
	 *
	 * @ORM\Column(name="meta_keywords", type="text", nullable=true)
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
	 * @var string
	 *
	 * @ORM\Column(name="meta_robots", type="string", length=56, nullable=true)
	 */
	protected $metaRobots;

	/**
	 * {@inheritdoc}
	 */
	public function setMetaTitle(string $metaTitle = null): SeoInterface
	{
		$this->metaTitle = $metaTitle;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMetaTitle(): ?string
	{
		return $this->metaTitle;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setMetaKeywords(string $metaKeywords = null): SeoInterface
	{
		$this->metaKeywords = $metaKeywords;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMetaKeywords(): ?string
	{
		return $this->metaKeywords;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setMetaDescription(string $metaDescription = null): SeoInterface
	{
		$this->metaDescription = $metaDescription;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMetaDescription(): ?string
	{
		return $this->metaDescription;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setMetaRobots(string $metaRobots = null): SeoInterface
	{
		$this->metaRobots = $metaRobots;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMetaRobots(): ?string
	{
		return $this->metaRobots;
	}
}