<?php

namespace Duo\AdminBundle\Form\Seo;

use Duo\AdminBundle\Form\DataTransformer\Seo\MetaRobotsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MetaRobotsType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->addModelTransformer(new MetaRobotsTransformer());
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'multiple' => true,
			'choices' => [
				'No index' 			=> 'noindex',
				'No follow' 		=> 'nofollow',
				'No archive' 		=> 'noarchive',
				'No snippet' 		=> 'nosnippet',
				'No translate' 		=> 'notranslate',
				'No image index' 	=> 'noimageindex'
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return ChoiceType::class;
	}
}