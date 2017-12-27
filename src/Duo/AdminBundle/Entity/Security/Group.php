<?php

namespace Duo\AdminBundle\Entity\Security;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Duo\AdminBundle\Entity\Behavior\IdTrait;
use Duo\AdminBundle\Entity\Behavior\TimeStampInterface;
use Duo\AdminBundle\Entity\Behavior\TimeStampTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="`group`",
 *     uniqueConstraints={
 *		   @ORM\UniqueConstraint(name="group_uniq", columns={ "name" })
 *	   },
 *     indexes={
 *		   @ORM\Index(name="name_idx", columns={ "name" })
 *	   }
 * )
 * @ORM\Entity()
 * @UniqueEntity(fields={ "name" }, message="duo.errors.name_used")
 */
class Group implements TimeStampInterface
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
	 * @var Collection
	 *
	 * @ORM\ManyToMany(targetEntity="Duo\AdminBundle\Entity\Security\Role", cascade={ "persist" })
	 * @ORM\JoinTable(name="group_to_role",
	 * 	   joinColumns={
	 *		   @ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")
	 *	   },
	 *     inverseJoinColumns={
	 *		   @ORM\JoinColumn(name="role_id", referencedColumnName="id", onDelete="CASCADE")
	 *	   }
	 * )
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
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return Group
	 */
	public function setName(string $name = null): Group
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
	 * Add role
	 *
	 * @param Role $role
	 *
	 * @return Group
	 */
	public function addRole(Role $role): Group
	{
		$this->roles[] = $role;

		return $this;
	}

	/**
	 * Remove role
	 *
	 * @param Role $role
	 *
	 * @return Group
	 */
	public function removeRole(Role $role): Group
	{
		$this->roles->removeElement($role);

		return $this;
	}

	/**
	 * Get roles
	 *
	 * @return ArrayCollection
	 */
	public function getRoles()
	{
		return $this->roles;
	}
}