<?php

namespace Duo\SecurityBundle\Form\Listing;

use Duo\AdminBundle\Form\Type\TabType;
use Duo\SecurityBundle\Form\Type\GroupChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertiesTabType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('active', CheckboxType::class, [
				'label' => 'duo_security.form.user.active.label'
			])
			->add('groups', GroupChoiceType::class, [
				'label' => 'duo_security.form.user.groups.label'
			]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'label' => 'duo_security.tab.properties'
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(): string
	{
		return TabType::class;
	}
}
