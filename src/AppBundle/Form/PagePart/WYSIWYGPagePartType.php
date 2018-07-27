<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\WYSIWYGPagePart;
use Duo\AdminBundle\Form\WYSIWYGType;
use Duo\PartBundle\Form\AbstractPartType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WYSIWYGPagePartType extends AbstractPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder->add('value', WYSIWYGType::class, [
			'label' => false
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => WYSIWYGPagePart::class,
			'model_class' => WYSIWYGPagePart::class
		]);
	}
}