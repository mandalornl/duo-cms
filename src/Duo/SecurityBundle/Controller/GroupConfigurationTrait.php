<?php

namespace Duo\SecurityBundle\Controller;

use Duo\SecurityBundle\Entity\Group;
use Duo\SecurityBundle\Form\GroupListingType;

trait GroupConfigurationTrait
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return Group::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return GroupListingType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'group';
	}
}