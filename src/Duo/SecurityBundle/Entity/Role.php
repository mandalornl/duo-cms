<?php

namespace Duo\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\TimeStampInterface;
use Duo\BehaviorBundle\Entity\TimeStampTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="role",
 *     uniqueConstraints={
 *		   @ORM\UniqueConstraint(name="role_uniq", columns={ "role" })
 *	   },
 *     indexes={
 *		   @ORM\Index(name="name_idx", columns={ "name" })
 *	   }
 * )
 * @ORM\Entity()
 * @UniqueEntity(fields={ "role" }, message="duo.security.errors.role_used")
 */
class Role implements RoleInterface, TimeStampInterface
{
	use IdTrait;
	use TimeStampTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=false)
	 * @Assert\NotBlank()
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="role", type="string", nullable=false)
	 * @Assert\NotBlank()
	 */
	private $role;

	/**
	 * {@inheritdoc}
	 */
	public function setName(string $name = null): RoleInterface
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
	public function setRole(string $role = null): RoleInterface
	{
		$this->role = $role;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRole(): ?string
	{
		return $this->role;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString(): string
	{
		return $this->name;
	}
}