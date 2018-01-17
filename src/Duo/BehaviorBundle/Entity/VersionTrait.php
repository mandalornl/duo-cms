<?php

namespace Duo\BehaviorBundle\Entity;

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
	 * {@inheritdoc}
	 */
	public function setVersion(int $version): VersionInterface
	{
		$this->version = $version;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getVersion(): int
	{
		return $this->version;
	}
}