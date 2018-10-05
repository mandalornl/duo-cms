<?php

namespace Duo\NodeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\CloneTrait;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\TimestampTrait;
use Duo\CoreBundle\Entity\Property\TranslateTrait;
use Duo\CoreBundle\Entity\Property\VersionTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity(fields={ "name" }, message="duo.node.errors.name_used")
 */
abstract class AbstractNode implements NodeInterface
{
	use IdTrait;
	use TranslateTrait;
	use CloneTrait;
	use TimestampTrait;
	use VersionTrait;

    /**
     * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=true, unique=true)
     */
    protected $name;

    /**
     * {@inheritdoc}
     */
    public function setName(?string $name): NodeInterface
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
		return $this->name;
	}
}