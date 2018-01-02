<?php

namespace Duo\AdminBundle\Form\Listing;

use Duo\AdminBundle\Entity\PageTranslation;
use Duo\AdminBundle\Form\ConfirmChoiceType;
use Duo\AdminBundle\Form\PublicationTabType;
use Duo\AdminBundle\Form\Seo\SeoTabType;
use Duo\AdminBundle\Form\TabsType;
use Duo\AdminBundle\Form\TabType;
use Duo\AdminBundle\Form\UrlType;
use Duo\AdminBundle\Form\WYSIWYGType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageTranslationType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$tabs = $builder->create('tabs', TabsType::class)
			->add(
				$builder->create('content', TabType::class, [
					'label' => 'duo.admin.tab.content'
				])
				->add('title', TextType::class, [
					'label' => 'duo.admin.form.page.title.label',
					'required' => false
				])
				->add('content', WYSIWYGType::class, [
					'label' => 'duo.admin.form.page.content.label',
					'required' => false
				])
			)
			->add(
				$builder->create('menu', TabType::class, [
					'label' => 'duo.admin.tab.menu'
				])
				->add('visible', CheckboxType::class, [
					'label' => 'duo.admin.form.page.visible.label',
					'required' => false
				])
				->add('slug', TextType::class, [
					'required' => false
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
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => PageTranslation::class
		]);
	}
}