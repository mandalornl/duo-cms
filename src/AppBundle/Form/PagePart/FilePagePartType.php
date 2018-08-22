<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\FilePagePart;
use Duo\MediaBundle\Form\MediaType;
use Duo\PartBundle\Form\AbstractPartType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilePagePartType extends AbstractPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder->add('media', MediaType::class, [
			'label' => false,
			'mediaType' => 'file'
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => FilePagePart::class,
			'model_class' => FilePagePart::class
		]);
	}
}