<?php

namespace Duo\AdminBundle\Configuration\Action;

class ListAction extends AbstractAction implements ListActionInterface
{
	/**
	 * ListAction constructor
	 *
	 * @param string $label
	 * @param string $route
	 * @param array $routeParameters [optional]
	 */
	public function __construct(string $label, string $route, array $routeParameters = [])
	{
		$this->label = $label;
		$this->route = $route;
		$this->routeParameters = $routeParameters;
	}
}