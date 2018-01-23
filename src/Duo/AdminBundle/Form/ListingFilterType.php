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
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		foreach ($options['filters'] as $filter)
		{
			/**
			 * @var FilterInterface $filter
			 */
			$builder->add($filter->getProperty(), $filter->getFormType(), array_merge([
				'required' => false
			], $filter->getFormOptions()));
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'filters' => []
		]);
	}
}