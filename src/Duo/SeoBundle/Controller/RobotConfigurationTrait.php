<?php

namespace Duo\SeoBundle\Controller;

use Duo\SeoBundle\Entity\Robot;
use Duo\SeoBundle\Form\RobotListingType;

trait RobotConfigurationTrait
{
	/**
	 * {@inheritdoc}
	 */
	protected function getEntityClass(): string
	{
		return Robot::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType(): ?string
	{
		return RobotListingType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'robot';
	}
}