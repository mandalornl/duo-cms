<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\ImagePagePart;
use Duo\MediaBundle\Form\MediaType;
use Duo\PartBundle\Form\AbstractPartType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ImagePagePartType extends AbstractPartType
{
	/**
	 * {@inheritdoc}
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
	 * {@inheritdoc}
	 */
	protected function getDataClass(): string
	{
		return ImagePagePart::class;
	}
}