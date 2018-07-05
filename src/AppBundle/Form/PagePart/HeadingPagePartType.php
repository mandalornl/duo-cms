<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\HeadingPagePart;
use Duo\PageBundle\Form\AbstractPagePartType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HeadingPagePartType extends AbstractPagePartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder->add('type', ChoiceType::class, [
			'required' => false,
			'placeholder' => false,
			'label' => 'app.form.heading_page_part.type.label',
			'choices' => [
				'Heading 1' => 'h1',
				'Heading 2' => 'h2',
				'Heading 3' => 'h3',
				'Heading 4' => 'h4',
				'Heading 5' => 'h5',
				'Heading 6' => 'h6'
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => HeadingPagePart::class,
			'model_class' => HeadingPagePart::class
		]);
	}
}