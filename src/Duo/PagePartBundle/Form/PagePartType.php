<?php

namespace Duo\PagePartBundle\Form;

use Duo\PagePartBundle\Configurator\PagePartConfiguratorInterface;
use Infinite\FormBundle\Form\Type\PolyCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PagePartType extends AbstractType
{
	/**
	 * @var PagePartConfiguratorInterface
	 */
	private $configurator;

	/**
	 * PagePartType constructor
	 *
	 * @param PagePartConfiguratorInterface $configurator
	 */
	public function __construct(PagePartConfiguratorInterface $configurator)
	{
		$this->configurator = $configurator;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		$icons = $this->configurator->getIcons();
		foreach ($icons as $class => $icon)
		{
			$icons[md5($class)] = $icon;
			unset($icons[$class]);
		}

		$view->vars['icons'] = $icons;
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$types = $this->configurator->getTypes();

		$typeOptions = array_map(function(string $label)
		{
			return [
				'label' => $label,
				'label_attr' => [
					'class' => 'sortable-move'
				],
				'required' => false
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
	 * {@inheritdoc}
	 */
	public function getParent(): string
	{
		return PolyCollectionType::class;
	}
}