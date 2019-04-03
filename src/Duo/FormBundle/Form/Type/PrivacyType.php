<?php

namespace Duo\FormBundle\Form\Type;

use Duo\PageBundle\Entity\PageInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrivacyType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function finishView(FormView $view, FormInterface $form, array $options): void
	{
		$view->vars['page'] = $options['page'];
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver
			->setDefaults([
				'page' => null
			])
			->setAllowedTypes('page', ['null', PageInterface::class]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(): string
	{
		return CheckboxType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getBlockPrefix(): string
	{
		return 'duo_privacy';
	}
}
