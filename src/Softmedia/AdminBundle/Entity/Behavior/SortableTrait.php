<?php

namespace Softmedia\AdminBundle\Entity\Behavior;

use Doctrine\ORM\Mapping as ORM;

trait SortableTrait
{
	/**
	 * @var int
	 *
	 * @ORM\Column(name="weight", type="integer", options={ "default" = 0 })
	 */
	protected $weight = 0;

	/**
	 * Set weight
	 *
	 * @param int $weight
	 *
	 * @return $this
	 */
	public function setWeight(int $weight = 0)
	{
		$this->weight = $weight;

		return $this;
	}

	/**
	 * Get weight
	 *
	 * @return int
	 */
	public function getWeight(): int
	{
		return $this->weight;
	}
}