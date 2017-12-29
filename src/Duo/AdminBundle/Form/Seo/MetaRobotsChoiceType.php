<?php

namespace Duo\AdminBundle\Form\Seo;

use Duo\AdminBundle\Form\DataTransformer\Seo\MetaRobotsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class MetaRobotsChoiceType extends AbstractType
{
	/**
	 * @var TranslatorInterface
	 */
	private $translator;

	/**
	 * MetaRobotsChoiceType constructor
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
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->addModelTransformer(new MetaRobotsTransformer());
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'multiple' => true,
			'choices' => [
				'No index' 			=> 'noindex',
				'No follow' 		=> 'nofollow',
				'No archive' 		=> 'noarchive',
				'No snippet' 		=> 'nosnippet',
				'No translate' 		=> 'notranslate',
				'No image index' 	=> 'noimageindex'
			],
			'attr' => [
				'data-placeholder' => $this->translator->trans('duo.admin.form.seo.meta_robots.placeholder')
			]
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