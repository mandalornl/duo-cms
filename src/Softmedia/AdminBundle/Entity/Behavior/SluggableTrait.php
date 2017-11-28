<?php

namespace Softmedia\AdminBundle\Entity\Behavior;

use Doctrine\ORM\Mapping as ORM;

trait SluggableTrait
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="slug", type="string", nullable=false)
	 */
	protected $slug;

	/**
	 * Set slug
	 *
	 * @param string $slug
	 *
	 * @return $this
	 */
	public function setSlug(string $slug = null)
	{
		$this->slug = $slug;

		return $this;
	}

	/**
	 * Get slug
	 *
	 * @return string
	 */
	public function getSlug(): ?string
	{
		return $this->slug;
	}

	/**
	 * Get value to slugify
	 *
	 * @return string
	 */
	abstract public function getValueToSlugify(): string;
}