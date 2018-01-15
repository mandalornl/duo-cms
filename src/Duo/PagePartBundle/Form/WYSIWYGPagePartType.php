<?php

namespace Duo\PagePartBundle\Form;

use Duo\PagePartBundle\Entity\WYSIWYGPagePart;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WYSIWYGPagePartType extends AbstractPagePartType
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
			'data_class' => WYSIWYGPagePart::class,
			'model_class' => WYSIWYGPagePart::class
		]);
	}
}