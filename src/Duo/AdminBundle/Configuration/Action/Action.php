<?php

namespace Duo\AdminBundle\Configuration\Action;

class Action implements ActionInterface
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
	private $routeParameters = [];

	/**
	 * @var string
	 */
	private $template;

	/**
	 * AbstractAction constructor
	 *
	 * @param string $label
	 */
	public function __construct(string $label)
	{
		$this->label = $label;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setLabel(string $label): ActionInterface
	{
		$this->label = $label;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getLabel(): string
	{
		return $this->label;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setRoute(string $route): ActionInterface
	{
		$this->route = $route;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRoute(): ?string
	{
		return $this->route;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setRouteParameters(array $routeParameters): ActionInterface
	{
		$this->routeParameters = $routeParameters;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRouteParameters(): array
	{
		return $this->routeParameters;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setTemplate(string $template): ActionInterface
	{
		$this->template = $template;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTemplate(): ?string
	{
		return $this->template;
	}
}
