<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\PartBundle\Form\Type\AbstractPartType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractFormPartType extends AbstractPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder->add('label', TextType::class, [
			'label' => 'duo_form.form.form_part.label.label'
		]);
	}
}
