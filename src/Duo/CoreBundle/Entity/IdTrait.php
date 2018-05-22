<?php

namespace Duo\CoreBundle\Entity;

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
	 * {@inheritdoc}
	 */
	public function getId(): ?int
	{
		return $this->id;
	}
}