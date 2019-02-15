<?php

namespace Duo\PageBundle\Form\Listing;

use Duo\AdminBundle\Form\Type\TabType;
use Duo\PageBundle\Form\Type\PagePartCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class ContentTabType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('title', TextType::class, [
				'label' => 'duo_page.form.page.title.label'
			])
			->add('parts', PagePartCollectionType::class, [
				'constraints' => [
					new Valid()
				]
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'label' => 'duo_page.tab.content'
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return TabType::class;
	}
}
