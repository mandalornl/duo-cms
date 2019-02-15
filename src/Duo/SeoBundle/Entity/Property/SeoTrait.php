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
	 * {@inheritdoc}
	 */
	public function setMetaTitle(?string $metaTitle): SeoInterface
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
	public function setMetaKeywords(?array $metaKeywords): SeoInterface
	{
		$this->metaKeywords = $metaKeywords;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMetaKeywords(): ?array
	{
		return $this->metaKeywords;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setMetaDescription(?string $metaDescription): SeoInterface
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
	public function setMetaRobots(?array $metaRobots): SeoInterface
	{
		$this->metaRobots = $metaRobots;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMetaRobots(): ?array
	{
		return $this->metaRobots;
	}
}
