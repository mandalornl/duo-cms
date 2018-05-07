<?php

namespace Duo\SecurityBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\TimestampTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="duo_group",
 *     uniqueConstraints={
 *		   @ORM\UniqueConstraint(name="group_uniq", columns={ "name" })
 *	   },
 *     indexes={
 *		   @ORM\Index(name="name_idx", columns={ "name" })
 *	   }
 * )
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
	 * @ORM\Column(name="name", type="string", nullable=false)
	 * @Assert\NotBlank()
	 */
	private $name;

	/**
	 * @var Collection
	 *
	 * @ORM\ManyToMany(targetEntity="Duo\SecurityBundle\Entity\Role", cascade={ "persist" })
	 * @ORM\JoinTable(name="duo_group_to_role",
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
	 * Group constructor
	 */
	public function __construct()
	{
		$this->roles = new ArrayCollection();
	}

	/**
	 * {@inheritdoc}
	 */
	public function setName(string $name = null): GroupInterface
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
		$this->roles[] = $role;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeRole(RoleInterface $role): GroupInterface
	{
		$this->roles->removeElement($role);

		return $this;
	}

	/**
	 * Get roles
	 *
	 * @param bool $flatten [optional]
	 *
	 * @return ArrayCollection|array
	 */
	public function getRoles(bool $flatten = false)
	{
		if ($flatten)
		{
			return $this->roles->map(function(RoleInterface $role)
			{
				return $role->getRole();
			})->toArray();
		}

		return $this->roles;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString(): string
	{
		return $this->name;
	}
}