<?php

namespace Duo\SecurityBundle\Controller\Listing;

use Duo\SecurityBundle\Entity\UserInterface;
use Duo\SecurityBundle\Form\Listing\UserType;

trait UserConfigurationTrait
{
	/**
	 * {@inheritDoc}
	 */
	protected function getEntityClass(): string
	{
		return UserInterface::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getFormType(): ?string
	{
		return UserType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getType(): string
	{
		return 'user';
	}
}
