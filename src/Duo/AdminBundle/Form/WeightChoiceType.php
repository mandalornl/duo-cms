<?php

namespace Duo\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class WeightChoiceType extends AbstractType
{
	/**
	 * @var TranslatorInterface
	 */
	private $translator;

	/**
	 * WeightChoiceType constructor
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
	public function configureOptions(OptionsResolver $resolver)
	{
		$choices = range(-100, 100);

		$resolver->setDefaults([
			'label' => 'duo.form.page.weight.label',
			'choices' => array_combine($choices, $choices),
			'attr' => [
				'data-placeholder' => $this->translator->trans('duo.form.weight_choice.placeholder')
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent()
	{
		return ChoiceType::class;
	}
}