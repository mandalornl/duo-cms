<?php

namespace Duo\AdminBundle\Form\Listing;

use Duo\AdminBundle\Configuration\Filter\FilterInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		foreach ($options['filters'] as $filter)
		{
			/**
			 * @var FilterInterface $filter
			 */
			$builder->add($filter->getUid(), $filter->getFormType(), array_merge($filter->getFormOptions(), [
				'label' => $filter->getLabel(),
				'required' => false
			]));
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'filters' => []
		]);
	}
}
