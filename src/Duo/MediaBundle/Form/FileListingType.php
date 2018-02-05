<?php

namespace Duo\MediaBundle\Form;

use Duo\MediaBundle\Entity\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileListingType extends AbstractType
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
			->add('file', FileType::class, [
				'mapped' => false,
				'label' => 'duo.media.form.file.file.label'
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
}