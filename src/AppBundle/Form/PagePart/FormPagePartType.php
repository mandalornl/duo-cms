<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\FormPagePart;
use Duo\FormBundle\Form\Type\FormAutoCompleteType;
use Duo\PartBundle\Form\Type\AbstractPartType;
use Symfony\Component\Form\FormBuilderInterface;

class FormPagePartType extends AbstractPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder->add('form', FormAutoCompleteType::class, [
			'label' => false
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getDataClass(): string
	{
		return FormPagePart::class;
	}
}