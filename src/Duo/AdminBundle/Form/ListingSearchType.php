<?php

namespace Duo\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;

class ListingSearchType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add('q', SearchType::class, [
			'label' => false,
			'required' => false,
			'attr' => [
				'placeholder' => 'duo.admin.form.listing_search.placeholder'
			]
		]);
	}
}