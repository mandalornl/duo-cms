<?php

namespace Duo\AdminBundle\Entity\Behavior;

use Doctrine\ORM\Mapping as ORM;

trait IdTrait
{
	/**
	 * @var int
	 *
	 * @ORM\Id()
	 * @ORM\Column(name="id", type="bigint")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * Get id
	 *
	 * @return int
	 */
	public function getId(): ?int
	{
		return $this->id;
	}
}