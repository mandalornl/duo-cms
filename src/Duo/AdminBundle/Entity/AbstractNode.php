<?php

namespace Duo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\AdminBundle\Entity\Behavior\BlameTrait;
use Duo\AdminBundle\Entity\Behavior\CloneTrait;
use Duo\AdminBundle\Entity\Behavior\IdTrait;
use Duo\AdminBundle\Entity\Behavior\NodeInterface;
use Duo\AdminBundle\Entity\Behavior\SoftDeleteTrait;
use Duo\AdminBundle\Entity\Behavior\SortTrait;
use Duo\AdminBundle\Entity\Behavior\TaxonomyTrait;
use Duo\AdminBundle\Entity\Behavior\TimeStampTrait;
use Duo\AdminBundle\Entity\Behavior\TranslateTrait;
use Duo\AdminBundle\Entity\Behavior\VersionTrait;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractNode implements NodeInterface
{
	use IdTrait;
	use TaxonomyTrait;
	use BlameTrait;
	use SoftDeleteTrait;
	use TranslateTrait;
	use SortTrait;
	use CloneTrait;
	use VersionTrait;
	use TimeStampTrait;

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
		return $this->name;
	}
}