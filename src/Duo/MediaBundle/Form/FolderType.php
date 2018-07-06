<?php

namespace Duo\MediaBundle\Form;

use Duo\MediaBundle\Entity\Folder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FolderType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('name', TextType::class, [
				'label' => 'duo.media.form.folder.name.label'
			])
			->add('parent', FolderAutoCompleteType::class, [
				'label' => 'duo.media.form.folder.parent.label'
			])
		;
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Folder::class
		]);
	}
}