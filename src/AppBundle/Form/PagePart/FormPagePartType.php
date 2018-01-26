<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\FormPagePart;
use Duo\FormBundle\Form\FormAutoCompleteType;
use Duo\PartBundle\Form\AbstractPartType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FormPagePartType extends AbstractPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder, $options);

		$builder->add('form', FormAutoCompleteType::class, [
			'label' => false,
			'constraints' => [
				new NotBlank()
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => FormPagePart::class,
			'model_class' => FormPagePart::class
		]);
	}
}