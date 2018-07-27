<?php

namespace Duo\PageBundle\Form\Listing;

use Duo\AdminBundle\Form\PublicationTabType;
use Duo\AdminBundle\Form\TabsType;
use Duo\AdminBundle\Form\TabType;
use Duo\PageBundle\Entity\PageTranslationInterface;
use Duo\PageBundle\Form\PagePartCollectionType;
use Duo\SeoBundle\Form\SeoTabType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class PageTranslationType extends AbstractType
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
					'label' => 'duo.page.form.page.title.label'
				])
				->add('parts', PagePartCollectionType::class, [
					'constraints' => [
						new Valid()
					]
				])
			)
			->add(
				$builder->create('menu', TabType::class, [
					'label' => 'duo.page.tab.menu'
				])
				->add('visibleMenu', CheckboxType::class, [
					'label' => 'duo.page.form.page.visible_menu.label',
					'required' => false
				])
				->add('slug', TextType::class, [
					'label' => 'duo.page.form.page.slug.label',
					'required' => false,
					// empty string is allowed for existing entities e.g. home
					'empty_data' => !$options['isNew'] ? '' : null
				])
				->add('url', TextType::class, [
					'label' => 'duo.page.form.page.url.label',
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
			'data_class' => PageTranslationInterface::class
		]);
	}
}