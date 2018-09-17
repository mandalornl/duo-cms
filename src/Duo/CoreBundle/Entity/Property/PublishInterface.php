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
	 * @param \DateTime $publishAt
	 *
	 * @return PublishInterface
	 */
	public function setPublishAt(?\DateTime $publishAt): PublishInterface;

	/**
	 * Get publishAt
	 *
	 * @return \DateTime
	 */
	public function getPublishAt(): ?\DateTime;

	/**
	 * Set unpublishAt
	 *
	 * @param \DateTime $unpublishAt
	 *
	 * @return PublishInterface
	 */
	public function setUnpublishAt(?\DateTime $unpublishAt): PublishInterface;

	/**
	 * Get unpublishAt
	 *
	 * @return \DateTime
	 */
	public function getUnpublishAt(): ?\DateTime;

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