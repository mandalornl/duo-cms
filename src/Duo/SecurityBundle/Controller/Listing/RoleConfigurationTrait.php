<?php

namespace Duo\SecurityBundle\Controller\Listing;

use Duo\SecurityBundle\Entity\Role;
use Duo\SecurityBundle\Form\Listing\RoleType;

trait RoleConfigurationTrait
{
	/**
	 * {@inheritDoc}
	 */
	protected function getEntityClass(): string
	{
		return Role::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getFormType(): ?string
	{
		return RoleType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getType(): string
	{
		return 'role';
	}
}
