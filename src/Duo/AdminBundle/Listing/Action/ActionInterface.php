<?php

namespace Duo\AdminBundle\Listing\Action;

interface ActionInterface
{
	/**
	 * Set label
	 *
	 * @param string $label
	 *
	 * @return ActionInterface
	 */
	public function setLabel(string $label): ActionInterface;

	/**
	 * Get label
	 *
	 * @return string
	 */
	public function getLabel(): ?string;

	/**
	 * Set route
	 *
	 * @param string $route
	 *
	 * @return ActionInterface
	 */
	public function setRoute(string $route): ActionInterface;

	/**
	 * Get route
	 *
	 * @return string
	 */
	public function getRoute(): ?string;

	/**
	 * Set routeParameters
	 *
	 * @param array $routeParameters
	 *
	 * @return ActionInterface
	 */
	public function setRouteParameters(array $routeParameters): ActionInterface;

	/**
	 * Get routeParameters
	 *
	 * @return array
	 */
	public function getRouteParameters(): array;

	/**
	 * Set template
	 *
	 * @param string $template
	 *
	 * @return ActionInterface
	 */
	public function setTemplate(string $template): ActionInterface;

	/**
	 * Get template
	 *
	 * @return string
	 */
	public function getTemplate(): ?string;
}