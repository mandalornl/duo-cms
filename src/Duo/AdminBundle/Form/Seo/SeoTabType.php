<?php

namespace Duo\AdminBundle\Form\Seo;

use Duo\AdminBundle\Form\TabType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeoTabType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('metaTitle', MetaTitleType::class, [
				'label' => 'duo.admin.form.seo.meta_title.label',
				'required' => false
			])
			->add('metaDescription', MetaDescriptionType::class, [
				'label' => 'duo.admin.form.seo.meta_description.label',
				'required' => false,
			])
			->add('metaKeywords', TextType::class, [
				'label' => 'duo.admin.form.seo.meta_keywords.label',
				'required' => false
			])
			->add('metaRobots', MetaRobotsChoiceType::class, [
				'label' => 'duo.admin.form.seo.meta_robots.label',
				'required' => false
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'label' => 'duo.admin.tab.seo'
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
		return TabType::class;
	}
}