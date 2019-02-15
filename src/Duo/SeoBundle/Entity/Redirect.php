<?php

namespace Duo\SeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\Property\IdInterface;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\TimestampInterface;
use Duo\CoreBundle\Entity\Property\TimestampTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="duo_seo_redirect")
 * @ORM\Entity()
 * @UniqueEntity(fields={ "origin" }, message="duo_seo.errors.origin_used")
 */
class Redirect implements IdInterface, TimestampInterface
{
	use IdTrait;
	use TimestampTrait;

	/**
	 * @var bool
	 *
	 * @ORM\Column(name="active", type="boolean", options={ "default" = 1 })
	 */
	private $active = true;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="permanent", type="boolean", options={ "default" = 1 })
	 */
	private $permanent = true;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="origin", type="string", nullable=true, unique=true)
	 * @Assert\NotBlank()
	 */
	private $origin;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="target", type="string", nullable=true)
	 * @Assert\NotBlank()
	 */
	private $target;

	/**
	 * Set active
	 *
	 * @param bool $active
	 *
	 * @return Redirect
	 */
	public function setActive(bool $active): Redirect
	{
		$this->active = $active;

		return $this;
	}

	/**
	 * Is active
	 *
	 * @return bool
	 */
	public function isActive(): bool
	{
		return $this->active;
	}

	/**
	 * Set permanent
	 *
	 * @param bool $permanent
	 *
	 * @return Redirect
	 */
	public function setPermanent(bool $permanent = true): Redirect
	{
		$this->permanent = $permanent;

		return $this;
	}

	/**
	 * Is permanent
	 *
	 * @return bool
	 */
	public function isPermanent(): bool
	{
		return $this->permanent;
	}

	/**
	 * Set origin
	 * @param string $origin
	 *
	 * @return Redirect
	 */
	public function setOrigin(?string $origin): Redirect
	{
		$this->origin = $origin;

		return $this;
	}

	/**
	 * Get origin
	 *
	 * @return string
	 */
	public function getOrigin(): ?string
	{
		return $this->origin;
	}

	/**
	 * Set target
	 *
	 * @param string $target
	 *
	 * @return Redirect
	 */
	public function setTarget(?string $target): Redirect
	{
		$this->target = $target;

		return $this;
	}

	/**
	 * Get target
	 *
	 * @return string
	 */
	public function getTarget(): ?string
	{
		return $this->target;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString(): string
	{
		return $this->origin;
	}
}
