<?php

namespace Duo\FormBundle\Form;

use Duo\FormBundle\Entity\ChoiceFormPartInterface;
use Duo\FormBundle\Entity\FormPartInterface;
use Duo\FormBundle\Entity\TextFormPartInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormViewType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		foreach ($options['formParts'] as $index => $formPart)
		{
			$formOptions = [];

			if ($formPart instanceof FormPartInterface)
			{
				$formOptions = array_merge($formOptions, [
					'label' => $formPart->getLabel()
				]);
			}

			if ($formPart instanceof TextFormPartInterface)
			{
				$formOptions = array_merge($formOptions, [
					'required' => $formPart->getRequired(),
					'invalid_message' => $formPart->getErrorMessage(),
					'attr' => [
						'placeholder' => $formPart->getPlaceholder()
					]
				]);
			}

			if ($formPart instanceof ChoiceFormPartInterface)
			{
				$choices = explode(PHP_EOL, $formPart->getChoices());

				$formOptions = array_merge($formOptions, [
					'placeholder' => $formPart->getPlaceholder(),
					'choices' => array_combine($choices, $choices),
					'expanded' => $formPart->getExpanded(),
					'multiple' => $formPart->getMultiple()
				]);
			}

			$builder->add($index, $formPart->getFormType(), $formOptions);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'formParts' => []
		]);
	}
}