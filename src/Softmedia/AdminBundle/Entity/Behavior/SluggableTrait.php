<?php

namespace Softmedia\AdminBundle\Entity\Behavior;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait SluggableTrait
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="slug", type="string", nullable=true)
     * @Assert\NotBlank()
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