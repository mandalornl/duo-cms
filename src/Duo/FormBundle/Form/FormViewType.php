<?php

namespace Duo\FormBundle\Form;

use Duo\FormBundle\Entity\FormPart\ChoiceFormPartInterface;
use Duo\FormBundle\Entity\FormPart\FormPartInterface;
use Duo\FormBundle\Entity\FormPart\TextFormPartInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class FormViewType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		foreach ($options['formParts'] as $index => $formPart)
		{
			$formOptions = [];

			if ($formPart instanceof FormPartInterface)
			{
				$formOptions = array_replace_recursive($formOptions, [
					'label' => $formPart->getLabel()
				]);
			}

			if ($formPart instanceof TextFormPartInterface)
			{
				$formOptions = array_replace_recursive($formOptions, [
					'required' => $formPart->getRequired(),
					'constraints' => $formPart->getRequired() ? [
						$formPart->getErrorMessage() ? new NotBlank([
							'message' => $formPart->getErrorMessage()
						]) : new NotBlank()
					] : null,
					'attr' => [
						'placeholder' => $formPart->getPlaceholder()
					]
				]);
			}

			if ($formPart instanceof ChoiceFormPartInterface)
			{
				$choices = array_map('trim', explode(PHP_EOL, $formPart->getChoices()));
				$choices = array_combine($choices, $choices);

				$formOptions = array_replace_recursive($formOptions, [
					'placeholder' => $formPart->getPlaceholder(),
					'choices' => $choices,
					'constraints' => $formPart->getRequired() ? [
						$formPart->getErrorMessage() ? new Choice([
							'choices' => $choices,
							'message' => $formPart->getErrorMessage()
						]) : new Choice([
							'choices' => $choices
						])
					] : null,
					'expanded' => $formPart->getExpanded(),
					'multiple' => $formPart->getMultiple()
				]);
			}

			// overwrite/extend form options
			$formOptions = array_replace_recursive($formOptions, $formPart->getFormOptions());

			$builder->add($index, $formPart->getFormType(), $formOptions);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'formParts' => []
		]);
	}
}