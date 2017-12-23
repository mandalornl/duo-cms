<?php

namespace Duo\AdminBundle\Entity\Behavior;

use Doctrine\ORM\Mapping as ORM;

trait TimeStampTrait
{
	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="created_at", type="datetime", options={ "default" = "CURRENT_TIMESTAMP" })
	 */
	protected $createdAt;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="modified_at", type="datetime", options={ "default" = "CURRENT_TIMESTAMP" })
	 */
	protected $modifiedAt;

	/**
	 * {@inheritdoc}
	 */
	public function setCreatedAt(\DateTime $createdAt = null): TimeStampInterface
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCreatedAt(): ?\DateTime
	{
		return $this->createdAt;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setModifiedAt(\DateTime $modifiedAt = null): TimeStampInterface
	{
		$this->modifiedAt = $modifiedAt;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getModifiedAt(): ?\DateTime
	{
		return $this->modifiedAt;
	}

	/**
	 * On clone timestamps
	 */
	protected function onCloneTimestamps()
	{
		$this->createdAt = null;
		$this->modifiedAt = null;
	}
}