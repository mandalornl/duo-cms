<?php

namespace Duo\SeoBundle\Form;

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
			->add('origin', TextType::class, [
				'label' => 'duo.seo.form.redirect.origin.label'
			])
			->add('target', TextType::class, [
				'label' => 'duo.seo.form.redirect.target.label'
			])
			->add('permanent', CheckboxType::class, [
				'label' => 'duo.seo.form.redirect.permanent.label'
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