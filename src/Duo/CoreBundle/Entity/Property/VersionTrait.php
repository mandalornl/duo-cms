<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\ORM\Mapping as ORM;

trait VersionTrait
{
	/**
	 * @var int
	 *
	 * @ORM\Column(name="version", type="integer")
	 * @ORM\Version()
	 */
	protected $version = 1;

	/**
	 * {@inheritDoc}
	 */
	public function setVersion(int $version): VersionInterface
	{
		$this->version = $version;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getVersion(): int
	{
		return $this->version;
	}
}
