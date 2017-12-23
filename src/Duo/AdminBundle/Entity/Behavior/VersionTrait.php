<?php

namespace Duo\AdminBundle\Entity\Behavior;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait VersionTrait
{
	/**
	 * @var VersionInterface
	 */
	protected $version;

	/**
	 * @var Collection
	 */
	protected $versions;

	/**
	 * {@inheritdoc}
	 */
	public function setVersion(VersionInterface $version = null): VersionInterface
	{
		$this->version = $version;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getVersion(): ?VersionInterface
	{
		return $this->version;
	}

	/**
	 * {@inheritdoc}
	 */
	public function addVersion(VersionInterface $version): VersionInterface
	{
		$this->getVersions()->add($version);

		return $this;
	}

	/**
	 * {@inheritdoc
	 */
	public function removeVersion(VersionInterface $version): VersionInterface
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