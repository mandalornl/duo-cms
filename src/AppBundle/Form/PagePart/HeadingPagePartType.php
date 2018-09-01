<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\HeadingPagePart;
use Duo\PartBundle\Form\AbstractPartType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class HeadingPagePartType extends AbstractPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder
			->add('value', TextType::class, [
				'label' => false
			])
			->add('type', ChoiceType::class, [
				'required' => false,
				'placeholder' => false,
				'label' => 'app.form.heading_page_part.type.label',
				'choices' => [
					'app.form.heading_page_part.type.choices.heading_1' => 'h1',
					'app.form.heading_page_part.type.choices.heading_2' => 'h2',
					'app.form.heading_page_part.type.choices.heading_3' => 'h3',
					'app.form.heading_page_part.type.choices.heading_4' => 'h4',
					'app.form.heading_page_part.type.choices.heading_5' => 'h5',
					'app.form.heading_page_part.type.choices.heading_6' => 'h6'
				]
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getDataClass(): string
	{
		return HeadingPagePart::class;
	}
}