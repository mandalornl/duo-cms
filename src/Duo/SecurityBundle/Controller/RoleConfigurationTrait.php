<?php

namespace Duo\SecurityBundle\Controller;

use Duo\SecurityBundle\Entity\Role;
use Duo\SecurityBundle\Form\Listing\RoleType;

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
		return RoleType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'role';
	}
}