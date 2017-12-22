<?php

namespace Duo\AdminBundle\Entity\Behavior;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait VersionableTrait
{
	/**
	 * @var VersionableInterface
	 */
	protected $version;

	/**
	 * @var Collection
	 */
	protected $versions;

	/**
	 * {@inheritdoc}
	 */
	public function setVersion(VersionableInterface $version = null): VersionableInterface
	{
		$this->version = $version;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getVersion(): ?VersionableInterface
	{
		return $this->version;
	}

	/**
	 * {@inheritdoc}
	 */
	public function addVersion(VersionableInterface $version): VersionableInterface
	{
		$this->getVersions()->add($version);

		return $this;
	}

	/**
	 * {@inheritdoc
	 */
	public function removeVersion(VersionableInterface $version): VersionableInterface
	{
		$this->getVersions()->removeElement($version);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getVersions()
	{
		return $this->versions = $this->versions ?: new ArrayCollection();
	}
}