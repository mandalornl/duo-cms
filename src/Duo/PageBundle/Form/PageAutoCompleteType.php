<?php

namespace Duo\PageBundle\Form;

use Duo\AdminBundle\Form\AutoCompleteType;
use Duo\PageBundle\Entity\Page;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageAutoCompleteType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'label' => 'duo.page.form.page_autocomplete.label',
			'class' => Page::class,
			'routeName' => 'duo_page_autocomplete_page_url',
			'placeholder' => 'duo.page.form.page_autocomplete.placeholder',
			'propertyName' => function(Page $page)
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