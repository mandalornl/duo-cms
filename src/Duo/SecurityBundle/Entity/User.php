<?php

namespace Duo\SecurityBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\TimeStampInterface;
use Duo\BehaviorBundle\Entity\TimeStampTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="user",
 *     uniqueConstraints={
 *	       @ORM\UniqueConstraint(name="username", columns={ "username" }),
 *     	   @ORM\UniqueConstraint(name="email", columns={ "email" })
 *	   },
 *     indexes={
 *     	   @ORM\Index(name="email_idx", columns={ "email" }),
 *		   @ORM\Index(name="username_idx", columns={ "username" })
 *	   }
 * )
 * @ORM\Entity(repositoryClass="Duo\SecurityBundle\Repository\UserRepository")
 * @UniqueEntity(fields={ "username" }, message="duo.security.errors.username_used")
 * @UniqueEntity(fields={ "email" }, message="duo.security.errors.email_used")
 */
class User implements UserInterface, TimeStampInterface, \Serializable
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
	 * @ORM\Column(name="email", type="string", length=60, nullable=false)
	 * @Assert\Email()
	 */
    private $email;

    /**
     * @var string
	 *
	 * @ORM\Column(name="username", length=25, type="string", nullable=false)
	 * @Assert\NotBlank()
     */
    private $username;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="password", type="string", length=60, nullable=false)
	 */
    private $password;

	/**
	 * @var string
	 */
    private $plainPassword;

	/**
	 * @var string
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
	 * @ORM\ManyToMany(targetEntity="Duo\SecurityBundle\Entity\Group", cascade={ "persist" })
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
	 * Set email
	 *
	 * @param string $email
	 *
	 * @return User
	 */
    public function setEmail(string $email): User
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * Get email
	 *
	 * @return string
	 */
	public function getEmail(): ?string
	{
		return $this->email;
	}

    /**
     * {@inheritdoc}
     */
    public function setUsername(string $username = null): UserInterface
    {
        $this->username = $username;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

	/**
	 * {@inheritdoc}
	 */
    public function setPassword(string $password): UserInterface
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPassword(): ?string
	{
		return $this->password;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setPlainPassword(string $plainPassword): UserInterface
	{
		$this->plainPassword = $plainPassword;

		return $this;
	}

	/**
	 * {@inheritdoc}
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
	public function getSalt(): ?string
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
	 * {@inheritdoc}
	 */
	public function addGroup(GroupInterface $group): UserInterface
	{
		$this->groups[] = $group;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeGroup(GroupInterface $group): UserInterface
	{
		$this->groups->removeElement($group);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getGroups()
	{
		return $this->groups;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRoles(): array
	{
		$roles = [];

		foreach ($this->groups as $group)
		{
			/**
			 * @var GroupInterface $group
			 */
			$roles = array_merge($roles, $group->getRoles(true));
		}

		return $roles;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasRole(string $roleName): bool
	{
		return in_array($roleName, $this->getRoles());
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasRoles(array $roleNames): bool
	{
		return count(array_diff($roleNames, $this->getRoles())) === 0;
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
		return $this->username;
	}

	/**
	 * {@inheritdoc}
	 */
	public function serialize(): string
	{
		return serialize([
			$this->id,
			$this->username,
			$this->password,
			$this->active
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unserialize($serialized): void
	{
		list (
			$this->id,
			$this->username,
			$this->password,
			$this->active
		) = unserialize($serialized);
	}

	/**
	 * {@inheritdoc}
	 */
	public function isAccountNonExpired(): bool
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isAccountNonLocked(): bool
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isCredentialsNonExpired(): bool
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isEnabled(): bool
	{
		return $this->active;
	}
}
