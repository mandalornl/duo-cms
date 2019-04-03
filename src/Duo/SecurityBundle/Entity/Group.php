<?php

namespace Duo\SecurityBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\TimestampTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="duo_security_group")
 * @ORM\Entity()
 * @UniqueEntity(fields={ "name" }, message="duo_security.errors.name_used")
 */
class Group implements GroupInterface
{
	use IdTrait;
	use TimestampTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=true, unique=true)
	 * @Assert\NotBlank()
	 */
	private $name;

	/**
	 * @var Collection
	 *
	 * @ORM\ManyToMany(targetEntity="Duo\SecurityBundle\Entity\Role", cascade={ "persist" })
	 * @ORM\JoinTable(name="duo_security_group_to_role",
	 * 	   joinColumns={
	 *		   @ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")
	 *	   },
	 *     inverseJoinColumns={
	 *		   @ORM\JoinColumn(name="role_id", referencedColumnName="id", onDelete="CASCADE")
	 *	   }
	 * )
	 * @ORM\OrderBy({ "name" = "ASC" })
	 * @Assert\NotBlank()
	 */
	private $roles;

	/**
	 * {@inheritDoc}
	 */
	public function setName(?string $name): GroupInterface
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
	public function addRole(RoleInterface $role): GroupInterface
	{
		$this->getRoles()->add($role);

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function removeRole(RoleInterface $role): GroupInterface
	{
		$this->getRoles()->removeElement($role);

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRoles(): Collection
	{
		return $this->roles = $this->roles ?: new ArrayCollection();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRolesFlattened(): array
	{
		return $this->getRoles()->map(function(RoleInterface $role)
		{
			return $role->getRole();
		})->toArray();
	}

	/**
	 * {@inheritDoc}
	 */
	public function __toString(): string
	{
		return $this->name;
	}
}
