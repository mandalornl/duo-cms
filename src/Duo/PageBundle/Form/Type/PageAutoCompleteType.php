<?php

namespace Duo\PageBundle\Form\Type;

use Duo\AdminBundle\Form\Type\AutoCompleteType;
use Duo\PageBundle\Entity\PageInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageAutoCompleteType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'label' => 'duo.page.form.page_autocomplete.label',
			'class' => PageInterface::class,
			'routeName' => 'duo_page_autocomplete_page_url',
			'placeholder' => 'duo.page.form.page_autocomplete.placeholder',
			'propertyName' => function(PageInterface $page)
			{
				return "/{$page->translate()->getUrl()}";
			}
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return AutoCompleteType::class;
	}
}