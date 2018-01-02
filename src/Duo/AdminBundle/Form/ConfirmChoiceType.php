<?php

namespace Duo\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class ConfirmChoiceType extends AbstractType
{
	/**
	 * @var TranslatorInterface
	 */
	private $translator;

	/**
	 * ConfirmChoiceType constructor
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
		$resolver->setDefaults([
			'choices' => [
				'duo.admin.form.confirm_choice.choices.yes' => 1,
				'duo.admin.form.confirm_choice.choices.no' => 0
			],
			'data' => 1
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