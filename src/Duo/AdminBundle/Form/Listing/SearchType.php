<?php

namespace Duo\AdminBundle\Form\Listing;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType as CoreSearchType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add('q', CoreSearchType::class, [
			'label' => false,
			'required' => false,
			'attr' => [
				'placeholder' => 'duo_admin.form.listing_search.placeholder'
			]
		]);
	}
}
