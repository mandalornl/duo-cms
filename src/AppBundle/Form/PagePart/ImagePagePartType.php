<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\ImagePagePart;
use Duo\MediaBundle\Form\Type\MediaType;
use Duo\PartBundle\Form\Type\AbstractPartType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ImagePagePartType extends AbstractPartType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder
			->add('media', MediaType::class, [
				'label' => false,
				'mediaType' => 'image'
			])
			->add('altText', TextType::class, [
				'required' => false,
				'label' => 'app.form.image_page_part.alt.label'
			]);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getDataClass(): string
	{
		return ImagePagePart::class;
	}
}
