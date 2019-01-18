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
 * @UniqueEntity(fields={ "name" }, message="duo.security.errors.name_used")
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
	 * {@inheritdoc}
	 */
	public function setName(?string $name): GroupInterface
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
	public function addRole(RoleInterface $role): GroupInterface
	{
		$this->getRoles()->add($role);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeRole(RoleInterface $role): GroupInterface
	{
		$this->getRoles()->removeElement($role);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRoles(): Collection
	{
		return $this->roles = $this->roles ?: new ArrayCollection();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRolesFlattened(): array
	{
		return $this->getRoles()->map(function(RoleInterface $role)
		{
			return $role->getRole();
		})->toArray();
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString(): string
	{
		return $this->name;
	}
}
