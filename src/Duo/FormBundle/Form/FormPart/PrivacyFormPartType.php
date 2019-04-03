<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\FormBundle\Entity\FormPart\PrivacyFormPart;
use Duo\PageBundle\Form\Type\PageAutoCompleteType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PrivacyFormPartType extends AbstractFormPartType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder
			->add('required', CheckboxType::class, [
				'label' => 'duo_form.form.form_part.required.label',
				'required' => false
			])
			->add('errorMessage', TextType::class, [
				'label' => 'duo_form.form.form_part.error_message.label',
				'required' => false
			])
			->add('page', PageAutoCompleteType::class, [
				'label' => 'duo_form.form.form_part.page.label',
				'required' => false
			]);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getDataClass(): string
	{
		return PrivacyFormPart::class;
	}
}
