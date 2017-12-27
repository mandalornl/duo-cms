<?php

namespace Duo\AdminBundle\Entity\Security;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Duo\AdminBundle\Entity\Behavior\IdTrait;
use Duo\AdminBundle\Entity\Behavior\TimeStampInterface;
use Duo\AdminBundle\Entity\Behavior\TimeStampTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="user",
 *     uniqueConstraints={
 *	       @ORM\UniqueConstraint(name="username", columns={ "username" })
 *	   },
 *     indexes={
 *     	   @ORM\Index(name="name_idx", columns={ "name" }),
 *		   @ORM\Index(name="username_idx", columns={ "username" })
 *	   }
 * )
 * @ORM\Entity()
 * @UniqueEntity(fields={ "username" }, message="duo.errors.username_used")
 */
class User implements UserInterface, TimeStampInterface
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
	 * @ORM\Column(name="username", type="string", nullable=false)
	 * @Assert\NotBlank()
     */
    private $username;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="password", type="string", nullable=false)
	 */
    private $password;

	/**
	 * @var string
	 */
    private $plainPassword;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="salt", type="string", nullable=false)
	 */
	private $salt;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="active", type="boolean", options={ "default" = 0 })
	 */
	private $active = 0;

	/**
	 * @var Collection
	 *
	 * @ORM\ManyToMany(targetEntity="Duo\AdminBundle\Entity\Security\Group", cascade={ "persist" })
	 * @ORM\JoinTable(name="user_to_group",
	 *     joinColumns={
	 *		   @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
	 * 	   },
	 *     inverseJoinColumns={
	 *		   @ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")
	 * 	   }
	 * )
	 * @ORM\OrderBy({ "name" = "ASC" })
	 * @Assert\NotBlank()
	 */
    private $groups;

	/**
	 * User constructor
	 */
    public function __construct()
	{
		$this->groups = new ArrayCollection();
	}

	/**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName(string $name = null): User
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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username = null): User
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

	/**
	 * Set password
	 *
	 * @param string $password
	 *
	 * @return User
	 */
    public function setPassword(string $password = null): User
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * Get password
	 *
	 * @return string
	 */
	public function getPassword(): ?string
	{
		return $this->password;
	}

	/**
	 * Set plainPassword
	 *
	 * @param string $plainPassword
	 *
	 * @return User
	 */
	public function setPlainPassword(string $plainPassword): User
	{
		$this->plainPassword = $plainPassword;

		return $this;
	}

	/**
	 * Get plainPassword
	 *
	 * @return string
	 */
	public function getPlainPassword(): ?string
	{
		return $this->plainPassword;
	}

	/**
	 * Set salt
	 *
	 * @param string $salt
	 *
	 * @return User
	 */
	public function setSalt(string $salt): User
	{
		$this->salt = $salt;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSalt(): string
	{
		return $this->salt;
	}

	/**
	 * Set active
	 *
	 * @param bool $active
	 *
	 * @return User
	 */
	public function setActive(bool $active = false): User
	{
		$this->active = $active;

		return $this;
	}

	/**
	 * Get active
	 *
	 * @return bool
	 */
	public function getActive(): bool
	{
		return $this->active;
	}

	/**
	 * Add group
	 *
	 * @param Group $group
	 *
	 * @return User
	 */
	public function addGroup(Group $group): User
	{
		$this->groups[] = $group;

		return $this;
	}

	/**
	 * Remove group
	 *
	 * @param Group $group
	 *
	 * @return User
	 */
	public function removeGroup(Group $group): User
	{
		$this->groups->removeElement($group);

		return $this;
	}

	/**
	 * Get groups
	 *
	 * @return ArrayCollection
	 */
	public function getGroups()
	{
		return $this->groups;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRoles()
	{
		$roles = new ArrayCollection();

		foreach ($this->groups as $group)
		{
			/**
			 * @var Group $group
			 */
			foreach ($group->getRoles() as $role)
			{
				if ($roles->contains($role))
				{
					continue;
				}

				$roles[] = $role;
			}
		}

		return $roles->toArray();
	}

	/**
	 * {@inheritdoc}
	 */
	public function eraseCredentials(): void
	{
		$this->plainPassword = null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString(): string
	{
		return $this->name;
	}
}
