<?php

namespace Duo\NodeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\CloneTrait;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\TimeStampTrait;
use Duo\BehaviorBundle\Entity\TranslateTrait;
use Duo\BehaviorBundle\Entity\VersionTrait;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractNode implements NodeInterface
{
	use IdTrait;
	use TranslateTrait;
	use CloneTrait;
	use TimeStampTrait;
	use VersionTrait;

    /**
     * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=false)
	 * @Assert\NotBlank()
     */
    protected $name;

    /**
     * {@inheritdoc}
     */
    public function setName(string $name = null): NodeInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): ?string
    {
        return $this->name;
    }

	/**
	 * {@inheritdoc}
	 */
	public function __toString(): string
	{
		return "{$this->id}::{$this->name}";
	}
}