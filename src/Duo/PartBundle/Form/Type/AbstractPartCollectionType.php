<?php

namespace Duo\PartBundle\Form\Type;

use Duo\PartBundle\Configurator\PartConfiguratorInterface;
use Infinite\FormBundle\Form\Type\PolyCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractPartCollectionType extends AbstractType
{
	/**
	 * @var PartConfiguratorInterface
	 */
	protected $configurator;

	/**
	 * AbstractPartCollectionType constructor
	 *
	 * @param PartConfiguratorInterface $configurator
	 */
	public function __construct(PartConfiguratorInterface $configurator)
	{
		$this->configurator = $configurator;
	}

	/**
	 * {@inheritDoc}
	 */
	public function buildView(FormView $view, FormInterface $form, array $options): void
	{
		$view->vars['icons'] = $this->configurator->getIcons(true);
		$view->vars['sections'] = $this->configurator->getSections(true);
		$view->vars['routeName'] = $this->getRouteName();
		$view->vars['routeParameters'] = $this->getRouteParameters();
	}

	/**
	 * {@inheritDoc}
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$types = $this->configurator->getTypes();

		$typeOptions = array_map(function(string $label)
		{
			return [
				'label' => $label,
				'label_attr' => [
					'class' => 'sortable-handle'
				]
			];
		}, $types);

		$resolver->setDefaults([
			'label' => false,
			'types' => array_keys($types),
			'types_options' => $typeOptions,
			'allow_add' => true,
			'allow_delete' => true,
			'by_reference' => false
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(): string
	{
		return PolyCollectionType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getBlockPrefix(): string
	{
		return 'duo_part_collection';
	}

	/**
	 * Get route name
	 *
	 * @return string
	 */
	abstract protected function getRouteName(): string;

	/**
	 * Get route parameters
	 *
	 * @return array
	 */
	protected function getRouteParameters(): array
	{
		return [];
	}
}
