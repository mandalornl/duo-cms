<?php

namespace Duo\CoreBundle\Entity\Property;

use Symfony\Component\Security\Core\User\UserInterface;

interface PublishInterface
{
	/**
	 * @var int
	 */
	const NONE = 0;

	/**
	 * @var int
	 */
	const ALL = 1;

	/**
	 * @var int
	 */
	const PARTIAL = 2;

	/**
	 * Set publishAt
	 *
	 * @param \DateTimeInterface $publishAt
	 *
	 * @return PublishInterface
	 */
	public function setPublishAt(?\DateTimeInterface $publishAt): PublishInterface;

	/**
	 * Get publishAt
	 *
	 * @return \DateTimeInterface
	 */
	public function getPublishAt(): ?\DateTimeInterface;

	/**
	 * Set unpublishAt
	 *
	 * @param \DateTimeInterface $unpublishAt
	 *
	 * @return PublishInterface
	 */
	public function setUnpublishAt(?\DateTimeInterface $unpublishAt): PublishInterface;

	/**
	 * Get unpublishAt
	 *
	 * @return \DateTimeInterface
	 */
	public function getUnpublishAt(): ?\DateTimeInterface;

	/**
	 * Set publishedBy
	 *
	 * @param UserInterface $publishedBy
	 *
	 * @return PublishInterface
	 */
	public function setPublishedBy(?UserInterface $publishedBy): PublishInterface;

	/**
	 * Get publishedBy
	 *
	 * @return UserInterface
	 */
	public function getPublishedBy(): ?UserInterface;

	/**
	 * Set unpublishedBy
	 *
	 * @param UserInterface $unpublishedBy
	 *
	 * @return PublishInterface
	 */
	public function setUnpublishedBy(?UserInterface $unpublishedBy): PublishInterface;

	/**
	 * Get unpublishedBy
	 *
	 * @return UserInterface
	 */
	public function getUnpublishedBy(): ?UserInterface;

	/**
	 * Publish
	 *
	 * @return PublishInterface
	 */
	public function publish(): PublishInterface;

	/**
	 * Unpublish
	 *
	 * @return PublishInterface
	 */
	public function unpublish(): PublishInterface;

	/**
	 * Is published
	 *
	 * @return bool
	 */
	public function isPublished(): bool;
}
