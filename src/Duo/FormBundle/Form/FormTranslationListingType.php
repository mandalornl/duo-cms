<?php

namespace Duo\FormBundle\Form;

use Duo\AdminBundle\Form\TabsType;
use Duo\AdminBundle\Form\TabType;
use Duo\AdminBundle\Form\WYSIWYGType;
use Duo\FormBundle\Entity\FormTranslation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class FormTranslationListingType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$tabs = $builder->create('tabs', TabsType::class)
			->add(
				$builder->create('message', TabType::class, [
					'label' => 'duo.form.tab.message'
				])
				->add('subject', TextType::class, [
					'label' => 'duo.form.form.form.subject.label'
				])
				->add('message', WYSIWYGType::class, [
					'label' => 'duo.form.form.form.message.label'
				])
			)
			->add(
				$builder->create('fields', TabType::class, [
					'label' => 'duo.form.tab.fields'
				])
				->add('parts', FormPartCollectionType::class, [
					'constraints' => [
						new Valid()
					]
				])
			);

		$builder->add($tabs);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => FormTranslation::class
		]);
	}
}