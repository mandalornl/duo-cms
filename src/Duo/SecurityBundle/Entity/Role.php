<?php

namespace Duo\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\TimestampTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="duo_security_role",
 *     indexes={
 *		   @ORM\Index(name="IDX_NAME", columns={ "name" })
 *	   }
 * )
 * @ORM\Entity()
 * @UniqueEntity(fields={ "role" }, message="duo_security.errors.role_used")
 */
class Role implements RoleInterface
{
	use IdTrait;
	use TimestampTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=true)
	 * @Assert\NotBlank()
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="role", type="string", nullable=true, unique=true)
	 * @Assert\NotBlank()
	 */
	private $role;

	/**
	 * {@inheritDoc}
	 */
	public function setName(?string $name): RoleInterface
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setRole(?string $role): RoleInterface
	{
		$this->role = $role;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRole(): ?string
	{
		return $this->role;
	}

	/**
	 * {@inheritDoc}
	 */
	public function __toString(): string
	{
		return $this->name;
	}
}
