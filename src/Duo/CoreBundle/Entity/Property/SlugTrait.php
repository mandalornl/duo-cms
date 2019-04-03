<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\ORM\Mapping as ORM;

trait SlugTrait
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="slug", type="string", nullable=true)
	 */
	protected $slug;

	/**
	 * {@inheritDoc}
	 */
	public function setSlug(?string $slug): SlugInterface
	{
		$this->slug = $slug;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getSlug(): ?string
	{
		return $this->slug;
	}
}
