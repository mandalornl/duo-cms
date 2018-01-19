<?php

namespace Duo\NodeBundle\Form;

use Duo\AdminBundle\Form\AutoCompleteType;
use Duo\NodeBundle\Entity\Page;
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
			'label' => 'duo.node.form.page_autocomplete.label',
			'class' => Page::class,
			'routeName' => 'duo_node_autocomplete_page_url',
			'placeholder' => 'duo.node.form.page_autocomplete.placeholder',
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