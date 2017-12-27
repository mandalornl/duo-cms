<?php

namespace Duo\AdminBundle\Form;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class AutoCompleteType extends AbstractType
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * @var RouterInterface
	 */
	private $router;

	/**
	 * AutoCompleteType constructor
	 *
	 * @param EntityManagerInterface $entityManager
	 * @param RouterInterface $router
	 */
	public function __construct(EntityManagerInterface $entityManager, RouterInterface $router)
	{
		$this->entityManager = $entityManager;
		$this->router = $router;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//$builder->addViewTransformer();
	}

	/**
	 * {@inheritdoc}
	 */
	public function finishView(FormView $view, FormInterface $form, array $options)
	{

	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{

	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix(): string
	{
		return 'duo_autocomplete';
	}
}