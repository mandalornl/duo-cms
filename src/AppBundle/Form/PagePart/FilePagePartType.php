<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\FilePagePart;
use Duo\MediaBundle\Form\Type\MediaType;
use Duo\PartBundle\Form\Type\AbstractPartType;
use Symfony\Component\Form\FormBuilderInterface;

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
	protected function getDataClass(): string
	{
		return FilePagePart::class;
	}
}