<?php

namespace Duo\NodeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\CloneTrait;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\DeleteTrait;
use Duo\BehaviorBundle\Entity\SortTrait;
use Duo\BehaviorBundle\Entity\TimeStampTrait;
use Duo\BehaviorBundle\Entity\TranslateTrait;
use Duo\BehaviorBundle\Entity\RevisionTrait;
use Duo\TaxonomyBundle\Entity\TaxonomyInterface;
use Duo\TaxonomyBundle\Entity\TaxonomyTrait;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractNode implements NodeInterface, TaxonomyInterface
{
	use IdTrait;
	use TaxonomyTrait;
	use DeleteTrait;
	use TranslateTrait;
	use SortTrait;
	use CloneTrait;
	use RevisionTrait;
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