<?php

namespace Duo\AdminBundle\Form;

use Duo\AdminBundle\Configuration\Filter\FilterInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListingFilterType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		foreach ($options['filters'] as $filter)
		{
			/**
			 * @var FilterInterface $filter
			 */
			$builder->add($filter->getProperty(), $filter->getFormType(), array_merge($filter->getFormOptions(), [
				'required' => false
			]));
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'filters' => []
		]);
	}
}