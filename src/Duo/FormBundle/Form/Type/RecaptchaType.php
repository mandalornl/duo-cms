<?php

namespace Duo\FormBundle\Form\Type;

use Duo\FormBundle\Validator\Constraints\Recaptcha;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecaptchaType extends AbstractType
{
	/**
	 * @var string
	 */
	private $key;

	/**
	 * RecaptchaType constructor
	 *
	 * @param string $key
	 */
	public function __construct(string $key)
	{
		$this->key = $key;
	}

	/**
	 * {@inheritdoc}
	 */
	public function finishView(FormView $view, FormInterface $form, array $options): void
	{
		$view->vars['autoHideBadge'] = $options['autoHideBadge'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver
			->setDefaults([
				'mapped' => false,
				'required' => true,
				'error_bubbling' => true,
				'attr' => function(Options $options)
				{
					return [
						'data-key' => $this->key,
						'data-use-recaptcha-net' => (int)$options['useRecaptchaNet'],
						'data-auto-hide-badge' => (int)$options['autoHideBadge']
					];
				},
				'constraints' => function(Options $options)
				{
					return [
						new Recaptcha([
							'expectedHostname' => $options['expectedHostname'],
							'expectedAction' => $options['expectedAction'],
							'scoreThreshold' => $options['scoreThreshold'],
							'challengeTimeout' => $options['challengeTimeout'],
							'remoteIp' => $options['remoteIp']
						])
					];
				},
				'expectedHostname' => null,
				'expectedAction' => null,
				'scoreThreshold' => null,
				'challengeTimeout' => null,
				'remoteIp' => null,
				'useRecaptchaNet' => false,
				'autoHideBadge' => false
			])
			->setAllowedTypes('expectedHostname', ['null', 'string'])
			->setAllowedTypes('expectedAction', ['null', 'string'])
			->setAllowedTypes('scoreThreshold', ['null', 'float', 'int'])
			->setAllowedTypes('challengeTimeout', ['null', 'int'])
			->setAllowedTypes('remoteIp', ['null', 'string'])
			->setAllowedTypes('useRecaptchaNet', 'bool')
			->setAllowedTypes('autoHideBadge', 'bool');
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return HiddenType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix(): string
	{
		return 'duo_recaptcha';
	}
}
