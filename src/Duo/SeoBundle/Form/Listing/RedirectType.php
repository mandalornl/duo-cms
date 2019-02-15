<?php

namespace Duo\SeoBundle\Form\Listing;

use Duo\SeoBundle\Entity\Redirect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RedirectType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('active', CheckboxType::class, [
				'label' => 'duo_seo.form.redirect.active.label',
				'required' => false
			])
			->add('permanent', CheckboxType::class, [
				'label' => 'duo_seo.form.redirect.permanent.label',
				'required' => false
			])
			->add('origin', TextType::class, [
				'label' => 'duo_seo.form.redirect.origin.label'
			])
			->add('target', TextType::class, [
				'label' => 'duo_seo.form.redirect.target.label'
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Redirect::class
		]);
	}
}
