<?php

namespace Softmedia\AdminBundle\Entity\Behavior;

use Doctrine\ORM\Mapping as ORM;

trait IdableTrait
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