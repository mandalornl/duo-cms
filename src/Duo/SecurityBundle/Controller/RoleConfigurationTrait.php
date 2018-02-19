<?php

namespace Duo\SecurityBundle\Controller;

use Duo\SecurityBundle\Entity\Role;
use Duo\SecurityBundle\Form\RoleListingType;

trait RoleConfigurationTrait
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return Role::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return RoleListingType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'role';
	}
}