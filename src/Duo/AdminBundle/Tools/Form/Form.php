<?php

namespace Duo\AdminBundle\Tools\Form;

use Symfony\Component\Form\FormInterface;

class Form
{
	/**
	 * Get view data
	 *
	 * @param FormInterface $form
	 *
	 * @return array
	 */
	public static function getViewData(FormInterface $form): array
	{
		$result = [];

		/**
		 * @var FormInterface $child
		 */
		foreach ($form as $key => $child)
		{
			if ($child->count())
			{
				$result[$key] = self::getViewData($child);

				continue;
			}

			if (is_array($viewData = $child->getViewData()))
			{
				$config = $child->getConfig();

				$isEqual = $child->getNormData() === $viewData;
				$isMultiple = $config->hasOption('multiple') && $config->getOption('multiple');

				$viewData = $isEqual ? array_values($viewData) : array_keys($viewData);
				$viewData = $isMultiple ? $viewData : current($viewData);
			}

			$result[$key] = $viewData;
		}

		return $result;
	}

	/**
	 * Form constructor
	 */
	private function __construct() {}
}