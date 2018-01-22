<?php

namespace Duo\AdminBundle\Form\Filter;

use Duo\AdminBundle\Form\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class DateTimeFilterType extends AbstractFilterType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('operator', ChoiceType::class, [
				'choices' => [
					'duo.admin.listing.filter.before' => 'before',
					'duo.admin.listing.filter.after' => 'after'
				]
			])
			->add('value', DateTimeType::class, [
				'required' => false
			]);
	}
}