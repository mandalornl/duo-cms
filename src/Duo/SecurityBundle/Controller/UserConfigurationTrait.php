<?php

namespace Duo\SecurityBundle\Controller;

use Duo\SecurityBundle\Entity\User;
use Duo\SecurityBundle\Form\Listing\UserType;

trait UserConfigurationTrait
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return User::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return UserType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'user';
	}
}