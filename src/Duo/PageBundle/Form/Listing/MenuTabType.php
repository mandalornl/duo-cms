<?php

namespace Duo\PageBundle\Form\Listing;

use Duo\AdminBundle\Form\Type\TabType;
use Duo\AdminBundle\Form\Type\UrlType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuTabType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('slug', TextType::class, [
				'label' => 'duo_page.form.page.slug.label',
				'required' => false,
				// empty string is allowed for existing entities e.g. home
				'empty_data' => $options['isNew'] ? null : ''
			])
			->add('url', UrlType::class, [
				'label' => 'duo_page.form.page.url.label',
				'required' => false,
				'attr' => [
					'readonly' => true
				]
			]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver
			->setDefaults([
				'label' => 'duo_page.tab.menu',
				'isNew' => true
			])
			->setAllowedTypes('isNew', 'bool');
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(): string
	{
		return TabType::class;
	}
}
