<?php

namespace Duo\PartBundle\Form;

use Duo\PartBundle\Entity\WYSIWYGPart;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WYSIWYGPartType extends AbstractPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder, $options);

		$builder->add('value', TextareaType::class, [
			'label' => false,
			'required' => false,
			'attr' => [
				'class' => 'wysiwyg',
				'rows' => 6
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => WYSIWYGPart::class,
			'model_class' => WYSIWYGPart::class
		]);
	}
}