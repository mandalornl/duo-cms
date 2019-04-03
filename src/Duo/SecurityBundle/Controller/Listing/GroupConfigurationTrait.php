<?php

namespace Duo\SecurityBundle\Controller\Listing;

use Duo\SecurityBundle\Entity\Group;
use Duo\SecurityBundle\Form\Listing\GroupType;

trait GroupConfigurationTrait
{
	/**
	 * {@inheritDoc}
	 */
	protected function getEntityClass(): string
	{
		return Group::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getFormType(): ?string
	{
		return GroupType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getType(): string
	{
		return 'group';
	}
}
