<?php

namespace Duo\PageBundle\Form\Listing;

use Duo\AdminBundle\Form\Listing\PublicationTabType;
use Duo\AdminBundle\Form\Type\TabsType;
use Duo\PageBundle\Entity\PageTranslationInterface;
use Duo\SeoBundle\Form\Listing\SeoTabType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageTranslationType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add(
			$builder->create('tabs', TabsType::class)
				->add($builder->create('content', ContentTabType::class))
				->add($builder->create('menu', MenuTabType::class, [
					'isNew' => $options['isNew']
				]))
				->add($builder->create('publication', PublicationTabType::class))
				->add($builder->create('seo', SeoTabType::class))
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => PageTranslationInterface::class,
			'isNew' => true
		]);

		$resolver->setAllowedTypes('isNew', 'bool');
	}
}
