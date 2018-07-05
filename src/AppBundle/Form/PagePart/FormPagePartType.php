<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\FormPagePart;
use Duo\FormBundle\Form\FormAutoCompleteType;
use Duo\PageBundle\Form\AbstractPagePartType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormPagePartType extends AbstractPagePartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder
			->add('value', HiddenType::class, [
				'data' => '-' // prevent parent validator constraint from triggering
			])
			->add('form', FormAutoCompleteType::class, [
				'label' => false
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => FormPagePart::class,
			'model_class' => FormPagePart::class
		]);
	}
}