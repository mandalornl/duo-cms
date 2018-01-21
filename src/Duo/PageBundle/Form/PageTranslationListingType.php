<?php

namespace Duo\PageBundle\Form;

use Duo\AdminBundle\Form\PublicationTabType;
use Duo\AdminBundle\Form\TabsType;
use Duo\AdminBundle\Form\TabType;
use Duo\AdminBundle\Form\UrlType;
use Duo\PageBundle\Entity\PageTranslation;
use Duo\SeoBundle\Form\SeoTabType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PageTranslationListingType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$tabs = $builder->create('tabs', TabsType::class)
			->add(
				$builder->create('content', TabType::class, [
					'label' => 'duo.page.tab.content'
				])
				->add('title', TextType::class, [
					'label' => 'duo.page.form.page.title.label',
					'required' => true,
					'constraints' => [
						new NotBlank()
					]
				])
				->add('parts', PagePartCollectionType::class)
			)
			->add(
				$builder->create('menu', TabType::class, [
					'label' => 'duo.page.tab.menu'
				])
				->add('visible', CheckboxType::class, [
					'label' => 'duo.page.form.page.visible.label',
					'required' => false
				])
				->add('slug', TextType::class, [
					'required' => false,
					// empty string is allowed for existing entities e.g. home
					'empty_data' => !$options['isNew'] ? '' : null
				])
				->add('url', UrlType::class, [
					'required' => false,
					'disabled' => true
				])
			)
			->add($builder->create('publication', PublicationTabType::class))
			->add($builder->create('seo', SeoTabType::class));

		$builder->add($tabs);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'isNew' => true,
			'data_class' => PageTranslation::class
		]);
	}
}