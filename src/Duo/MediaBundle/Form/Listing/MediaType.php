<?php

namespace Duo\MediaBundle\Form\Listing;

use Duo\MediaBundle\Entity\Media;
use Duo\MediaBundle\Form\PreviewType;
use Duo\TaxonomyBundle\Form\TaxonomyChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MediaType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('name', TextType::class, [
				'required' => false,
				'label' => 'duo.media.form.media.name.label'
			])
			->add('taxonomies', TaxonomyChoiceType::class, [
				'required' => false,
				'label' => 'duo.media.form.media.taxonomies.label'
			])
			->add('file', FileType::class, [
				'mapped' => false,
				'label' => 'duo.media.form.media.file.label',
				'constraints' => [
					new NotBlank()
				]
			])
			->add('preview', PreviewType::class, [
				'data' => $options['data']
			]);

		$builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event)
		{
			$data = $event->getData();

			if (!$data instanceof Media || $data->getId() === null)
			{
				return;
			}

			$event->getForm()->remove('file');
		});
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Media::class
		]);
	}
}