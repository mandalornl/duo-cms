<?php

namespace Duo\AdminBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;

abstract class AbstractFilterType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix(): string
	{
		return 'duo_filter';
	}
}