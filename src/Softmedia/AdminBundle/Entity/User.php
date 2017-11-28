<?php

namespace Softmedia\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softmedia\AdminBundle\Entity\Behavior\BlameableUserInterface;
use Softmedia\AdminBundle\Entity\Behavior\IdableTrait;
use Softmedia\AdminBundle\Entity\Behavior\TimeStampableTrait;

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
 */
class User implements BlameableUserInterface
{
	use IdableTrait;
	use TimeStampableTrait;

	/**
     * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string
	 *
	 * @ORM\Column(name="username", type="string", nullable=false)
     */
    private $username;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="password", type="string", nullable=true)
	 */
    private $password;

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
	 * {@inheritdoc}
	 */
	public function __toString(): string
	{
		return $this->name;
	}
}
