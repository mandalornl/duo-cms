<?php

namespace Duo\MediaBundle\Form;

use Duo\AdminBundle\Form\AutoCompleteType;
use Duo\MediaBundle\Entity\Folder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FolderAutoCompleteType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'label' => 'duo.media.form.folder_autocomplete.label',
			'class' => Folder::class,
			'routeName' => 'duo_media_autocomplete_folder_url',
			'placeholder' => 'duo.media.form.folder_autocomplete.placeholder',
			'propertyName' => function(Folder $folder)
			{
				return "/{$folder->getUrl()}";
			}
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return AutoCompleteType::class;
	}
}