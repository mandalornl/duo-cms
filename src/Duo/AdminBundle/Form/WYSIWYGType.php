<?php

namespace Duo\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WYSIWYGType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'attr' => [
				'class' => 'wysiwyg',
				'rows' => 6
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return TextareaType::class;
	}
}