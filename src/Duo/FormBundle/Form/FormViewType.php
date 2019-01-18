<?php

namespace Duo\FormBundle\Form;

use Duo\FormBundle\Entity\FormPart\ChoiceFormPartInterface;
use Duo\FormBundle\Entity\FormPart\FormPartInterface;
use Duo\FormBundle\Entity\FormPart\TextFormPartInterface;
use Duo\FormBundle\Form\Type\HoneypotType;
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
		// TODO: randomize position and name
		$builder->add(count($options['formParts']) + 1, HoneypotType::class);

		foreach ($options['formParts'] as $index => $formPart)
		{
			$formOptions = [];

			if ($formPart instanceof FormPartInterface)
			{
				$formOptions = array_replace_recursive($formOptions, [
					'label' => $formPart->getLabel()
				]);
			}

			if ($formPart instanceof ChoiceFormPartInterface)
			{
				$formOptions = array_replace_recursive($formOptions, $this->getChoiceFormOptions($formPart));
			}
			else
			{
				if ($formPart instanceof TextFormPartInterface)
				{
					$formOptions = array_replace_recursive($formOptions, $this->getTextFormOptions($formPart));
				}
			}

			// overwrite/extend form options
			$formOptions = array_replace_recursive($formOptions, $formPart->getFormOptions());

			$builder->add($index, $formPart->getFormType(), $formOptions);
		}
	}

	/**
	 * Get text form options
	 *
	 * @param TextFormPartInterface $formPart
	 *
	 * @return array
	 */
	private function getTextFormOptions(TextFormPartInterface $formPart): array
	{
		return [
			'required' => $formPart->isRequired(),
			'constraints' => $formPart->isRequired() ? [
				$formPart->getErrorMessage() ? new NotBlank([
					'message' => $formPart->getErrorMessage()
				]) : new NotBlank()
			] : null,
			'attr' => [
				'placeholder' => $formPart->getPlaceholder()
			]
		];
	}

	/**
	 * Get choice form options
	 *
	 * @param ChoiceFormPartInterface $formPart
	 *
	 * @return array
	 */
	private function getChoiceFormOptions(ChoiceFormPartInterface $formPart): array
	{
		$choices = array_map('trim', explode(PHP_EOL, $formPart->getChoices()));
		$choices = array_combine($choices, $choices);

		return [
			'required' => $formPart->isRequired(),
			'placeholder' => $formPart->getPlaceholder(),
			'choices' => $choices,
			'constraints' => $formPart->isRequired() ? [
				$formPart->getErrorMessage() ? new Choice([
					'choices' => $choices,
					'message' => $formPart->getErrorMessage()
				]) : new Choice([
					'choices' => $choices
				])
			] : null,
			'expanded' => $formPart->isExpanded(),
			'multiple' => $formPart->isMultiple()
		];
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
