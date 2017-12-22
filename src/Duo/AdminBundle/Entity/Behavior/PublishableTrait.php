<?php

namespace Duo\AdminBundle\Entity\Behavior;

trait PublishableTrait
{
	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="published", type="boolean", options={ "default" = 0 })
	 */
	protected $published = 0;

	/**
	 * Set published
	 *
	 * @param boolean $published
	 *
	 * @return PublishableInterface
	 */
	public function setPublished(bool $published = false): PublishableInterface
	{
		$this->published = $published;

		return $this;
	}

	/**
	 * Get published
	 *
	 * @return boolean
	 */
	public function getPublished(): bool
	{
		return $this->published;
	}
}