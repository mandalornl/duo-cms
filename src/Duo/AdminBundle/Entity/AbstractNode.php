<?php

namespace Duo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\AdminBundle\Entity\Behavior\BlameableTrait;
use Duo\AdminBundle\Entity\Behavior\CloneableInterface;
use Duo\AdminBundle\Entity\Behavior\CloneableTrait;
use Duo\AdminBundle\Entity\Behavior\IdableTrait;
use Duo\AdminBundle\Entity\Behavior\SoftDeletableInterface;
use Duo\AdminBundle\Entity\Behavior\SoftDeletableTrait;
use Duo\AdminBundle\Entity\Behavior\SortableInterface;
use Duo\AdminBundle\Entity\Behavior\SortableTrait;
use Duo\AdminBundle\Entity\Behavior\TaxonomyTrait;
use Duo\AdminBundle\Entity\Behavior\TimeStampableInterface;
use Duo\AdminBundle\Entity\Behavior\TimeStampableTrait;
use Duo\AdminBundle\Entity\Behavior\TranslatableInterface;
use Duo\AdminBundle\Entity\Behavior\TranslatableTrait;
use Duo\AdminBundle\Entity\Behavior\VersionableInterface;
use Duo\AdminBundle\Entity\Behavior\VersionableTrait;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractNode implements SoftDeletableInterface, TranslatableInterface, SortableInterface, CloneableInterface, VersionableInterface, TimeStampableInterface
{
	use IdableTrait;
	use BlameableTrait;
	use TaxonomyTrait;
	use SoftDeletableTrait;
	use TranslatableTrait;
	use SortableTrait;
	use CloneableTrait;
	use VersionableTrait;
	use TimeStampableTrait;

    /**
     * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=false)
	 * @Assert\NotBlank()
     */
    protected $name;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return AbstractNode
     */
    public function setName(string $name = null): AbstractNode
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
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