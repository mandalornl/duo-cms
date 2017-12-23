<?php

namespace Duo\AdminBundle\Entity\Behavior;

use Doctrine\ORM\Mapping as ORM;

trait SortTrait
{
	/**
	 * @var int
	 *
	 * @ORM\Column(name="weight", type="integer", options={ "default" = 0 })
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