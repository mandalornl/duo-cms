<?php

namespace Duo\AdminBundle\Listing\Action;

abstract class AbstractAction implements ActionInterface
{
	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var string
	 */
	private $route;

	/**
	 * @var array
	 */
	private $routeParameters;

	/**
	 * @var string
	 */
	private $template;

	/**
	 * ItemAction constructor
	 *
	 * @param string $label
	 * @param string $route
	 * @param array $routeParameters [optional]
	 * @param string $template [optional]
	 */
	public function __construct(string $label, string $route, array $routeParameters = [], string $template = null)
	{
		$this->label = $label;
		$this->route = $route;
		$this->routeParameters = $routeParameters;
		$this->template = $template;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setLabel(string $label): ActionInterface
	{
		$this->label = $label;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLabel(): ?string
	{
		return $this->label;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setRoute(string $route): ActionInterface
	{
		$this->route = $route;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRoute(): ?string
	{
		return $this->route;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setRouteParameters(array $routeParameters): ActionInterface
	{
		$this->routeParameters = $routeParameters;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRouteParameters(): array
	{
		return $this->routeParameters;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setTemplate(string $template): ActionInterface
	{
		$this->template = $template;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTemplate(): ?string
	{
		return $this->template;
	}
}