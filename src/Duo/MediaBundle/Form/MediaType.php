<?php

namespace Duo\MediaBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use Duo\AdminBundle\Form\DataTransformer\EntityToIdTransformer;
use Duo\MediaBundle\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{
	/**
	 * @var EntityManagerInterface
	 */
	private $manager;

	/**
	 * MediaType constructor
	 *
	 * @param EntityManagerInterface $manager
	 */
	public function __construct(EntityManagerInterface $manager)
	{
		$this->manager = $manager;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->addViewTransformer(new EntityToIdTransformer(
			$this->manager,
			Media::class
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		$view->vars['mediaType'] = $options['mediaType'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setRequired('mediaType');

		$resolver->setAllowedValues('mediaType', [
			'file',
			'image'
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return TextType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix(): string
	{
		return 'duo_media';
	}
}