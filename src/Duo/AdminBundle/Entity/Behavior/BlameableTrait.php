<?php

namespace Duo\AdminBundle\Entity\Behavior;

use Duo\AdminBundle\Entity\User;

trait BlameableTrait
{
	/**
	 * @var BlameableUserInterface
	 */
	protected $createdBy;

	/**
	 * @var BlameableUserInterface
	 */
	protected $modifiedBy;

	/**
	 * @var BlameableUserInterface
	 */
	protected $deletedBy;

	/**
	 * Set createdBy
	 *
	 * @param BlameableUserInterface $createdBy
	 *
	 * @return $this
	 */
	public function setCreatedBy(BlameableUserInterface $createdBy = null)
	{
		$this->createdBy = $createdBy;

		return $this;
	}

	/**
	 * Get createdBy
	 *
	 * @return BlameableUserInterface
	 */
	public function getCreatedBy(): ?BlameableUserInterface
	{
		return $this->createdBy;
	}

	/**
	 * Set modified by
	 *
	 * @param BlameableUserInterface $modifiedBy
	 *
	 * @return $this
	 */
	public function setModifiedBy(BlameableUserInterface $modifiedBy = null)
	{
		$this->modifiedBy = $modifiedBy;

		return $this;
	}

	/**
	 * Get modifiedBy
	 *
	 * @return BlameableUserInterface
	 */
	public function getModifiedBy(): ?BlameableUserInterface
	{
		return $this->modifiedBy;
	}

	/**
	 * Set deletedBy
	 *
	 * @param BlameableUserInterface $deletedBy
	 *
	 * @return $this
	 */
	public function setDeletedBy(BlameableUserInterface $deletedBy = null)
	{
		$this->deletedBy = $deletedBy;

		return $this;
	}

	/**
	 * Get deletedBy
	 *
	 * @return BlameableUserInterface
	 */
	public function getDeletedBy(): ?BlameableUserInterface
	{
		return $this->deletedBy;
	}

	/**
	 * Get blameable entity class name
	 *
	 * @return string
	 */
	public static function getBlameableUserEntityClassName(): string
	{
		return User::class;
	}

	/**
	 * On clone blameable
	 */
	protected function onCloneBlameable()
	{
		$this->createdBy = null;
		$this->modifiedBy = null;
		$this->deletedBy = null;
	}
}