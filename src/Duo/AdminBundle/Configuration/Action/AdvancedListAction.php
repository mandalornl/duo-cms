<?php

namespace Duo\AdminBundle\Configuration\Action;

class AdvancedListAction extends AbstractAction implements ListActionInterface
{
	/**
	 * AdvancedListAction constructor
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