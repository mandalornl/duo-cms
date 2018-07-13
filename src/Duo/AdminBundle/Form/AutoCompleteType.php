<?php

namespace Duo\AdminBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use Duo\AdminBundle\Form\DataTransformer\EntityToPropertyTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class AutoCompleteType extends AbstractType
{
	/**
	 * @var EntityManagerInterface
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
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->addViewTransformer(new EntityToPropertyTransformer(
			$this->entityManager,
			$options['class'],
			$options['propertyName'],
			$options['multiple']
		), true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function finishView(FormView $view, FormInterface $form, array $options): void
	{
		$view->vars['remotePath'] =
			$options['remotePath'] ?: $this->router->generate(
				$options['routeName'],
				$options['routeParams'],
				$options['routeType']
			);

		foreach ([
			'multiple',
			'placeholder',
			'excludeSelf'
		 ] as $varName)
		{
			$view->vars[$varName] = $options[$varName];
		}

		if ($options['multiple'])
		{
			$view->vars['full_name'] .= '[]';
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'multiple' => false,
			'compound' => false,
			'placeholder' => '',

			'class' => null,
			'propertyName' => null,

			'remotePath' => null,

			'routeName' => null,
			'routeParams' => [],
			'routeType' => RouterInterface::ABSOLUTE_PATH,

			'excludeSelf' => false
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix(): string
	{
		return 'duo_autocomplete';
	}
}