<?php

namespace Duo\SeoBundle\Controller\Listing;

use Duo\SeoBundle\Entity\Robot;
use Duo\SeoBundle\Form\Listing\RobotType;

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
		return RobotType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType(): string
	{
		return 'robot';
	}
}