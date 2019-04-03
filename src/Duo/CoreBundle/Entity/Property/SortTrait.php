<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\ORM\Mapping as ORM;

trait SortTrait
{
	/**
	 * @var int
	 *
	 * @ORM\Column(name="weight", type="smallint", nullable=true)
	 */
	protected $weight;

	/**
	 * {@inheritDoc}
	 */
	public function setWeight(?int $weight): SortInterface
	{
		$this->weight = $weight;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getWeight(): ?int
	{
		return $this->weight;
	}
}
