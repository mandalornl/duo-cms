<?php

namespace Duo\AdminBundle\Form\Filter;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class StringFilterType extends AbstractFilterType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('operator', ChoiceType::class, [
				'choices' => [
					'duo_admin.listing.filter.contains' => 'contains',
					'duo_admin.listing.filter.not_contains' => 'notContains',
					'duo_admin.listing.filter.equals' => 'equals',
					'duo_admin.listing.filter.not_equals' => 'notEquals',
					'duo_admin.listing.filter.starts_with' => 'startsWith',
					'duo_admin.listing.filter.not_starts_with' => 'notStartsWith',
					'duo_admin.listing.filter.ends_with' => 'endsWith',
					'duo_admin.listing.filter.not_ends_with' => 'notEndsWith'
				]
			])
			->add('value', TextType::class, [
				'required' => false
			]);
	}
}
