<?php

namespace Duo\SecurityBundle\Controller;

use Duo\SecurityBundle\Entity\UserInterface;
use Duo\SecurityBundle\Form\Listing\UserType;

trait UserConfigurationTrait
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return UserInterface::class;
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