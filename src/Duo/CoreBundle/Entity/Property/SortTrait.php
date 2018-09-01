<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\ORM\Mapping as ORM;

trait SortTrait
{
	/**
	 * @var int
	 *
	 * @ORM\Column(name="weight", type="smallint", options={ "default" = 0 })
	 */
	protected $weight;

	/**
	 * {@inheritdoc}
	 */
	public function setWeight(int $weight = null): SortInterface
	{
		$this->weight = $weight;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getWeight(): ?int
	{
		return $this->weight;
	}
}