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
class Role implements TimeStampInterface
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
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return Role
	 */
	public function setName(string $name = null): Role
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * Set role
	 *
	 * @param string $role
	 *
	 * @return Role
	 */
	public function setRole(string $role = null): Role
	{
		$this->role = $role;

		return $this;
	}

	/**
	 * Get role
	 *
	 * @return string
	 */
	public function getRole(): ?string
	{
		return $this->role;
	}
}