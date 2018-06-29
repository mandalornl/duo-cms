<?php

namespace Duo\AdminBundle\Form\Filter;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class NumericFilterType extends AbstractFilterType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('operator', ChoiceType::class, [
				'choices' => [
					'duo.admin.listing.filter.equals' => 'equals',
					'duo.admin.listing.filter.not_equals' => 'notEquals',
					'duo.admin.listing.filter.greater_or_equals' => 'greaterOrEquals',
					'duo.admin.listing.filter.greater' => 'greater',
					'duo.admin.listing.filter.less' => 'less',
					'duo.admin.listing.filter.less_or_equals' => 'lessOrEquals'
				]
			])
			->add('value', TextType::class, [
				'required' => false
			]);
	}
}