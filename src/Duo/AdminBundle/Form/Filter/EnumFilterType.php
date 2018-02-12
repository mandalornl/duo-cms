<?php

namespace Duo\AdminBundle\Form\Filter;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class EnumFilterType extends AbstractFilterType
{
	/**
	 * @var TranslatorInterface
	 */
	private $translator;

	/**
	 * EnumFilterType constructor
	 *
	 * @param TranslatorInterface $translator
	 */
	public function __construct(TranslatorInterface $translator)
	{
		$this->translator = $translator;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('operator', ChoiceType::class, [
				'choices' => [
					'duo.admin.listing.filter.contains' => 'contains',
					'duo.admin.listing.filter.not_contains' => 'notContains'
				]
			])
			->add('value', ChoiceType::class, [
				'choices' => $options['choices'],
				'multiple' => true,
				'attr' => [
					'data-placeholder' => $this->translator->trans('duo.admin.form.enum_filter.placeholder')
				]
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'choices' => []
		]);

		$resolver->setRequired('choices');
	}
}