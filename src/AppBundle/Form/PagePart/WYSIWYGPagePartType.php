<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\WYSIWYGPagePart;
use Duo\AdminBundle\Form\WYSIWYGType;
use Duo\PageBundle\Form\AbstractPagePartType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class WYSIWYGPagePartType extends AbstractPagePartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder, $options);

		$builder->add('value', WYSIWYGType::class, [
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
			'data_class' => WYSIWYGPagePart::class,
			'model_class' => WYSIWYGPagePart::class
		]);
	}
}