<?php

namespace Duo\PageBundle\Form\Filter;

use Duo\AdminBundle\Form\Filter\AbstractFilterType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class PublishedFilterType extends AbstractFilterType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add('value', ChoiceType::class, [
			'required' => false,
			'empty_data' => null,
			'placeholder' => 'duo.page.form.published_filter.placeholder',
			'choices' => [
				'duo.page.form.published_filter.choices.yes' => 1,
				'duo.page.form.published_filter.choices.no' => 0
			]
		]);
	}
}
