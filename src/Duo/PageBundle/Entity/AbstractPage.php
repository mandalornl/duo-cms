<?php

namespace Duo\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\CloneTrait;
use Duo\CoreBundle\Entity\Property\DeleteTrait;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\RevisionTrait;
use Duo\CoreBundle\Entity\Property\SortTrait;
use Duo\CoreBundle\Entity\Property\TimestampTrait;
use Duo\CoreBundle\Entity\Property\TranslateTrait;
use Duo\CoreBundle\Entity\Property\TreeTrait;
use Duo\CoreBundle\Entity\Property\VersionTrait;
use Duo\DraftBundle\Entity\Property\DraftTrait;
use Duo\TaxonomyBundle\Entity\Property\TaxonomyTrait;

abstract class AbstractPage implements PageInterface
{
	use IdTrait;
	use TranslateTrait;
	use CloneTrait;
	use TimestampTrait;
	use VersionTrait;
	use DeleteTrait;
	use RevisionTrait;
	use SortTrait;
	use TreeTrait;
	use TaxonomyTrait;
	use DraftTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=true)
	 */
	protected $name;

	/**
	 * {@inheritdoc}
	 */
	public function setName(?string $name): PageInterface
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