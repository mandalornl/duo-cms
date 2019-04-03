<?php

namespace Duo\SeoBundle\Controller\Listing;

use Duo\SeoBundle\Entity\Robot;
use Duo\SeoBundle\Form\Listing\RobotType;

trait RobotConfigurationTrait
{
	/**
	 * {@inheritDoc}
	 */
	protected function getEntityClass(): string
	{
		return Robot::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getFormType(): ?string
	{
		return RobotType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getType(): string
	{
		return 'robot';
	}
}
