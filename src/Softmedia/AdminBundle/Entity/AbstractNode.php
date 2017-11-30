<?php

namespace Softmedia\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softmedia\AdminBundle\Entity\Behavior\BlameableTrait;
use Softmedia\AdminBundle\Entity\Behavior\CloneableInterface;
use Softmedia\AdminBundle\Entity\Behavior\CloneableTrait;
use Softmedia\AdminBundle\Entity\Behavior\IdableTrait;
use Softmedia\AdminBundle\Entity\Behavior\SluggableTrait;
use Softmedia\AdminBundle\Entity\Behavior\SoftDeletableTrait;
use Softmedia\AdminBundle\Entity\Behavior\SortableInterface;
use Softmedia\AdminBundle\Entity\Behavior\SortableTrait;
use Softmedia\AdminBundle\Entity\Behavior\TaxonomyTrait;
use Softmedia\AdminBundle\Entity\Behavior\TimeStampableTrait;
use Softmedia\AdminBundle\Entity\Behavior\TranslatableInterface;
use Softmedia\AdminBundle\Entity\Behavior\TranslatableTrait;

abstract class AbstractNode implements TranslatableInterface, CloneableInterface, SortableInterface
{
	use IdableTrait;
	use SluggableTrait;
	use BlameableTrait;
	use SoftDeletableTrait;
	use TranslatableTrait;
	use TaxonomyTrait;
	use SortableTrait;
	use CloneableTrait;
	use TimeStampableTrait;

    /**
     * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=false)
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
	public function getValueToSlugify(): string
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