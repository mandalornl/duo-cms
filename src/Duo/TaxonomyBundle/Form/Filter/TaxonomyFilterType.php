<?php

namespace Duo\TaxonomyBundle\Form\Filter;

use Duo\AdminBundle\Form\Filter\AbstractFilterType;
use Duo\TaxonomyBundle\Form\TaxonomyChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class TaxonomyFilterType extends AbstractFilterType
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
					'duo.admin.listing.filter.not_contains' => 'notContains'
				]
			])
			->add('value', TaxonomyChoiceType::class, [
				'required' => false
			]);
	}
}