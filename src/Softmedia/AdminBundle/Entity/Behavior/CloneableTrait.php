<?php

namespace Softmedia\AdminBundle\Entity\Behavior;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait CloneableTrait
{
	/**
	 * @var CloneableInterface
	 */
	protected $version;

	/**
	 * @var Collection
	 */
	protected $versions;

	/**
	 * {@inheritdoc}
	 */
	public function setVersion(CloneableInterface $version = null): CloneableInterface
	{
		$this->version = $version;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getVersion(): ?CloneableInterface
	{
		return $this->version;
	}

	/**
	 * {@inheritdoc}
	 */
	public function addVersion(CloneableInterface $version): CloneableInterface
	{
		$this->getVersions()->add($version);

		return $this;
	}

	/**
	 * {@inheritdoc
	 */
	public function removeVersion(CloneableInterface $version): CloneableInterface
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

	/**
	 * {@inheritdoc}
	 */
	public function __clone()
	{
		$reflectionClass = new \ReflectionClass($this);

		foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PROTECTED) as $reflectionMethod)
		{
			if (strpos($reflectionMethod->getName(), 'onClone') !== 0)
			{
				continue;
			}

			call_user_func([$this, $reflectionMethod->getName()]);
		}
	}
}