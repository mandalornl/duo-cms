<?php

namespace Duo\AdminBundle\Entity\Behavior;

use Duo\AdminBundle\Entity\User;

trait BlameTrait
{
	/**
	 * @var UserInterface
	 */
	protected $createdBy;

	/**
	 * @var UserInterface
	 */
	protected $modifiedBy;

	/**
	 * @var UserInterface
	 */
	protected $deletedBy;

	/**
	 * {@inheritdoc}
	 */
	public function setCreatedBy(UserInterface $createdBy = null): BlameInterface
	{
		$this->createdBy = $createdBy;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCreatedBy(): ?UserInterface
	{
		return $this->createdBy;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setModifiedBy(UserInterface $modifiedBy = null): BlameInterface
	{
		$this->modifiedBy = $modifiedBy;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getModifiedBy(): ?UserInterface
	{
		return $this->modifiedBy;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDeletedBy(UserInterface $deletedBy = null): BlameInterface
	{
		$this->deletedBy = $deletedBy;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDeletedBy(): ?UserInterface
	{
		return $this->deletedBy;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getBlameableUserEntityClassName(): string
	{
		return User::class;
	}
}