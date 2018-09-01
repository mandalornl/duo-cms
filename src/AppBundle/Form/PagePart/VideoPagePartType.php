<?php

namespace AppBundle\Form\PagePart;

use AppBundle\Entity\PagePart\VideoPagePart;
use Duo\PartBundle\Form\AbstractPartType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class VideoPagePartType extends AbstractPartType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		parent::buildForm($builder, $options);

		$builder->add('value', TextType::class, [
			'label' => false
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getDataClass(): string
	{
		return VideoPagePart::class;
	}
}