<?php

namespace Duo\MediaBundle\Form;

use Duo\MediaBundle\Entity\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType as CoreFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('name', TextType::class, [
				'required' => false,
				'label' => 'duo.media.form.file.name.label'
			])
			->add('folder', FolderAutoCompleteType::class, [
				'required' => false,
				'label' => 'duo.media.form.file.folder.label'
			])
			->add('file', CoreFileType::class, [
				'mapped' => false,
				'label' => 'duo.media.form.file.file.label'
			])
			->add('preview', PreviewType::class, [
				'data' => $options['data']
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => File::class
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix(): string
	{
		return 'duo_file';
	}
}