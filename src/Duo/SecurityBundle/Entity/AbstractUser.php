<?php

namespace Duo\SecurityBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\CloneTrait;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\TimestampTrait;
use Duo\CoreBundle\Entity\Property\UuidTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface as CoreUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(fields={ "username" }, message="duo_security.errors.username_used")
 * @UniqueEntity(fields={ "email" }, message="duo_security.errors.email_used")
 */
abstract class AbstractUser implements UserInterface, \Serializable
{
	use IdTrait;
	use UuidTrait;
	use TimestampTrait;
	use CloneTrait;

	/**
     * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=true)
	 * @Assert\NotBlank()
     */
    protected $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=128, nullable=true, unique=true)
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
    protected $email;

    /**
     * @var string
	 *
	 * @ORM\Column(name="username", type="string", length=128, nullable=true, unique=true)
	 * @Assert\NotBlank()
     */
    protected $username;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="password", type="string", length=128, nullable=true)
	 */
    protected $password;

	/**
	 * @var string
	 */
    protected $plainPassword;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="password_token", type="string", length=64, nullable=true)
	 */
    protected $passwordToken;

	/**
	 * @var \DateTimeInterface
	 *
	 * @ORM\Column(name="password_token_requested_at", type="datetime", nullable=true)
	 */
    protected $passwordTokenRequestedAt;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="active", type="boolean", options={ "default" = 0 })
	 */
	protected $active = false;

	/**
	 * @var Collection
	 *
	 * @ORM\ManyToMany(targetEntity="Duo\SecurityBundle\Entity\Group", cascade={ "persist" })
	 * @ORM\JoinTable(name="duo_security_user_to_group",
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
    protected $groups;

	/**
     * {@inheritDoc}
     */
    public function setName(?string $name): UserInterface
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
    public function setEmail(string $email): UserInterface
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEmail(): ?string
	{
		return $this->email;
	}

    /**
     * {@inheritDoc}
     */
    public function setUsername(?string $username): UserInterface
    {
        $this->username = $username;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

	/**
	 * {@inheritDoc}
	 */
    public function setPassword(string $password): UserInterface
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPassword(): ?string
	{
		return $this->password;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setPlainPassword(string $plainPassword): UserInterface
	{
		$this->plainPassword = $plainPassword;
		$this->password = null;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPlainPassword(): ?string
	{
		return $this->plainPassword;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setPasswordToken(?string $passwordToken): UserInterface
	{
		$this->passwordToken = $passwordToken;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPasswordToken(): ?string
	{
		return $this->passwordToken;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setPasswordTokenRequestedAt(?\DateTimeInterface $passwordTokenRequestedAt): UserInterface
	{
		$this->passwordTokenRequestedAt = $passwordTokenRequestedAt;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPasswordTokenRequestedAt(): ?\DateTimeInterface
	{
		return $this->passwordTokenRequestedAt;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getSalt(): ?string
	{
		return null;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setActive(bool $active): UserInterface
	{
		$this->active = $active;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isActive(): bool
	{
		return $this->active;
	}

	/**
	 * {@inheritDoc}
	 */
	public function addGroup(GroupInterface $group): UserInterface
	{
	    if (!$this->hasGroup($group))
        {
            $this->getGroups()->add($group);
        }

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function removeGroup(GroupInterface $group): UserInterface
	{
	    if ($this->hasGroup($group))
        {
            $this->getGroups()->removeElement($group);
        }

		return $this;
	}

    /**
	 * {@inheritDoc}
	 */
	public function getGroups(): Collection
	{
		return $this->groups = $this->groups ?: new ArrayCollection();
	}

    /**
     * {@inheritDoc}
     */
    public function hasGroup(GroupInterface $group): bool
    {
        return $this->getGroups()->contains($group);
    }

    /**
	 * {@inheritDoc}
	 */
	public function getRoles(): array
	{
		$roles = [];

		foreach ($this->getGroups() as $group)
		{
			/**
			 * @var GroupInterface $group
			 */
			$roles = array_merge($roles, $group->getRolesFlattened());
		}

		return $roles;
	}

	/**
	 * {@inheritDoc}
	 */
	public function hasRole(string $roleName): bool
	{
		return in_array($roleName, $this->getRoles());
	}

	/**
	 * {@inheritDoc}
	 */
	public function hasRoles(array $roleNames): bool
	{
		return count(array_diff($roleNames, $this->getRoles())) === 0;
	}

	/**
	 * {@inheritDoc}
	 */
	public function eraseCredentials(): void
	{
		$this->plainPassword = null;
	}

	/**
	 * {@inheritDoc}
	 */
	public function __toString(): string
	{
		return $this->username;
	}

	/**
	 * {@inheritDoc}
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
	 * {@inheritDoc}
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
	 * {@inheritDoc}
	 */
	public function isAccountNonExpired(): bool
	{
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isAccountNonLocked(): bool
	{
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isCredentialsNonExpired(): bool
	{
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isEnabled(): bool
	{
		return $this->active;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isEqualTo(CoreUserInterface $user): bool
	{
		// NOTE: be sure these properties are also present in the (un)serialize methods
		return $this->password === $user->getPassword() && $this->username === $user->getUsername();
	}
}
