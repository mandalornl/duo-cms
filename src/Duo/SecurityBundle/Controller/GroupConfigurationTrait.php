<?php

namespace Duo\SecurityBundle\Controller;

use Duo\SecurityBundle\Entity\Group;
use Duo\SecurityBundle\Form\Listing\GroupType;

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
		return GroupType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'group';
	}
}