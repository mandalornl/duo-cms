<?php

namespace Duo\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait SlugTrait
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="slug", type="string", nullable=false)
	 */
	protected $slug;

	/**
	 * {@inheritdoc}
	 */
	public function setSlug(string $slug = null): SlugInterface
	{
		$this->slug = $slug;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSlug(): ?string
	{
		return $this->slug;
	}
}