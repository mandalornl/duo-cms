<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\ImagePagePart;
use Duo\MediaBundle\Form\MediaType;
use Duo\PartBundle\Form\AbstractPartType;
use Symfony\Component\Form\FormBuilderInterface;

class ImagePagePartType extends AbstractPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder->add('media', MediaType::class, [
			'label' => false,
			'mediaType' => 'image'
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getDataClass(): string
	{
		return ImagePagePart::class;
	}
}