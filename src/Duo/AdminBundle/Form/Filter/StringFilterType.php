<?php

namespace Duo\AdminBundle\Form\Filter;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class StringFilterType extends AbstractFilterType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('operator', ChoiceType::class, [
				'choices' => [
					'duo.admin.listing.filter.contains' => 'contains',
					'duo.admin.listing.filter.not_contains' => 'notContains',
					'duo.admin.listing.filter.equals' => 'equals',
					'duo.admin.listing.filter.not_equals' => 'notEquals',
					'duo.admin.listing.filter.starts_with' => 'startsWith',
					'duo.admin.listing.filter.not_starts_with' => 'notStartsWith',
					'duo.admin.listing.filter.ends_with' => 'endsWith',
					'duo.admin.listing.filter.not_ends_with' => 'notEndsWith'
				]
			])
			->add('value', TextType::class, [
				'required' => false
			]);
	}
}