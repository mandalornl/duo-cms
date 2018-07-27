<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\TextPagePart;
use Duo\PartBundle\Form\AbstractPartType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextPagePartType extends AbstractPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder->add('value', TextareaType::class, [
			'label' => false,
			'attr' => [
				'rows' => 6
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => TextPagePart::class,
			'model_class' => TextPagePart::class
		]);
	}
}