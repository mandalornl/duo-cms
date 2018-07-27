<?php

namespace Duo\AdminBundle\Configuration\Action;

class AdvancedItemAction extends AbstractAction implements ItemActionInterface
{
	/**
	 * AdvancedItemAction constructor
	 *
	 * @param string $label
	 * @param string $template
	 */
	public function __construct(string $label, string $template)
	{
		$this->label = $label;
		$this->template = $template;
	}
}