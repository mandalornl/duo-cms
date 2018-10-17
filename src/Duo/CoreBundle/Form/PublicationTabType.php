<?php

namespace Duo\CoreBundle\Form;

use Duo\AdminBundle\Form\DateTimeType;
use Duo\AdminBundle\Form\TabType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublicationTabType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('publishAt', DateTimeType::class, [
				'required' => false,
				'label' => 'duo.core.form.publication.publish_at'
			])
			->add('unpublishAt', DateTimeType::class, [
				'required' => false,
				'label' => 'duo.core.form.publication.unpublish_at'
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'label' => 'duo.core.tab.publication'
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return TabType::class;
	}
}