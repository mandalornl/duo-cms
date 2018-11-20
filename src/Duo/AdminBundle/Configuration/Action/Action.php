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
	public function getLabel(): string
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