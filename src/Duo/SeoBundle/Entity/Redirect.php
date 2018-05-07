<?php

namespace Duo\SeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\IdInterface;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\TimestampInterface;
use Duo\BehaviorBundle\Entity\TimestampTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="duo_redirect",
 *     uniqueConstraints={
 *		   @ORM\UniqueConstraint(name="origin_uniq", columns={ "origin" })
 *	   },
 *     indexes={
 *     	   @ORM\Index(name="origin_idx", columns={ "origin" })
 *     }
 * )
 * @ORM\Entity()
 * @UniqueEntity(fields={ "origin" }, message="duo.seo.errors.origin_used")
 */
class Redirect implements IdInterface, TimestampInterface
{
	use IdTrait;
	use TimestampTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="origin", type="string", nullable=false)
	 * @Assert\NotBlank()
	 */
	private $origin;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="target", type="string", nullable=false)
	 * @Assert\NotBlank()
	 */
	private $target;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="permanent", type="boolean", options={ "default" = 1 })
	 */
	private $permanent = true;

	/**
	 * Set origin
	 * @param string $origin
	 *
	 * @return Redirect
	 */
	public function setOrigin(string $origin = null): Redirect
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
	public function setTarget(string $target = null): Redirect
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
	 * Get permanent
	 *
	 * @return bool
	 */
	public function getPermanent(): bool
	{
		return $this->permanent;
	}
}