<?php

namespace Duo\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function finishView(FormView $view, FormInterface $form, array $options): void
	{
		$view->vars['multiple'] = $options['multiple'];

		if ($options['multiple'])
		{
			$view->vars['full_name'] .= '[]';
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver
			->setDefaults([
				'multiple' => true
			])
			->setAllowedTypes('multiple', 'bool');
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(): string
	{
		return TextType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getBlockPrefix(): string
	{
		return 'duo_tag';
	}
}
