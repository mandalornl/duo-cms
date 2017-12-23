<?php

namespace Duo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\AdminBundle\Entity\Behavior\BlameableTrait;
use Duo\AdminBundle\Entity\Behavior\CloneableTrait;
use Duo\AdminBundle\Entity\Behavior\IdableTrait;
use Duo\AdminBundle\Entity\Behavior\NodeInterface;
use Duo\AdminBundle\Entity\Behavior\SoftDeletableTrait;
use Duo\AdminBundle\Entity\Behavior\SortableTrait;
use Duo\AdminBundle\Entity\Behavior\TaxonomyTrait;
use Duo\AdminBundle\Entity\Behavior\TimeStampableTrait;
use Duo\AdminBundle\Entity\Behavior\TranslatableTrait;
use Duo\AdminBundle\Entity\Behavior\VersionableTrait;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractNode implements NodeInterface
{
	use IdableTrait;
	use TaxonomyTrait;
	use BlameableTrait;
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