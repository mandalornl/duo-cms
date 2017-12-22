<?php

namespace Duo\AdminBundle\Entity\Behavior;

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
	 * {@inheritdoc}
	 */
	public function setSlug(string $slug = null): SluggableInterface
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