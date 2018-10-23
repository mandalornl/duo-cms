<?php

namespace Duo\SecurityBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\CloneTrait;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\TimestampTrait;
use Duo\CoreBundle\Entity\Property\UuidTrait;
use Symfony\Component\Security\Core\User\UserInterface as CoreUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

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
	 * @ORM\Column(name="email", type="string", length=64, nullable=true)
	 * @Assert\Email()
	 */
    protected $email;

    /**
     * @var string
	 *
	 * @ORM\Column(name="username", type="string", length=64, nullable=true)
	 * @Assert\NotBlank()
     */
    protected $username;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="password", type="string", length=64, nullable=true)
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
	 * @var \DateTime
	 *
	 * @ORM\Column(name="password_requested_at", type="datetime", nullable=true)
	 */
    protected $passwordRequestedAt;

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
	 * @ORM\JoinTable(name="duo_user_to_group",
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
	 * User constructor
	 */
    public function __construct()
	{
		$this->groups = new ArrayCollection();
	}

	/**
     * {@inheritdoc}
     */
    public function setName(?string $name): UserInterface
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
    public function setEmail(string $email): UserInterface
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEmail(): ?string
	{
		return $this->email;
	}

    /**
     * {@inheritdoc}
     */
    public function setUsername(?string $username): UserInterface
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
		$this->password = null;

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
	 * {@inheritdoc}
	 */
	public function setPasswordToken(?string $passwordToken): UserInterface
	{
		$this->passwordToken = $passwordToken;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPasswordToken(): ?string
	{
		return $this->passwordToken;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setPasswordRequestedAt(?\DateTime $passwordRequestedAt): UserInterface
	{
		$this->passwordRequestedAt = $passwordRequestedAt;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPasswordRequestedAt(): ?\DateTime
	{
		return $this->passwordRequestedAt;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSalt(): ?string
	{
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setActive(bool $active): UserInterface
	{
		$this->active = $active;

		return $this;
	}

	/**
	 * {@inheritdoc}
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
	public function getGroups(): Collection
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

	/**
	 * {@inheritdoc}
	 */
	public function isEqualTo(CoreUserInterface $user): bool
	{
		// NOTE: be sure these properties are also present in the (un)serialize methods
		return $this->password === $user->getPassword() && $this->username === $user->getUsername();
	}
}