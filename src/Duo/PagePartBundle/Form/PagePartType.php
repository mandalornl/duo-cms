<?php

namespace Duo\PagePartBundle\Form;

use Infinite\FormBundle\Form\Type\PolyCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PagePartType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$types = [
			HeadingPagePartType::class => 'duo.pagepart.type.heading',
			TextareaPagePartType::class => 'duo.pagepart.type.textarea',
			WYSIWYGPagePartType::class => 'duo.pagepart.type.wysiwyg',
			VideoPagePartType::class => 'duo.pagepart.type.video'
		];

		$typeOptions = array_map(function(string $label)
		{
			return [
				'label' => $label,
				'label_attr' => [
					'class' => 'sortable-move'
				],
				'required' => false
			];
		}, $types);

		$resolver->setDefaults([
			'label' => false,
			'types' => array_keys($types),
			'types_options' => $typeOptions,
			'allow_add' => true,
			'allow_delete' => true,
			'by_reference' => false
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return PolyCollectionType::class;
	}
}