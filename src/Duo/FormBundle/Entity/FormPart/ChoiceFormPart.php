<?php

namespace Duo\FormBundle\Entity\FormPart;

use Duo\FormBundle\Entity\AbstractFormChoicePart;
use Duo\FormBundle\Form\FormPart\ChoiceFormPartType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ChoiceFormPart extends AbstractFormChoicePart
{
	/**
	 * Get form type
	 *
	 * @return string
	 */
	public function getFormType(): string
	{
		return ChoiceType::class;
	}

	/**
	 * Get part form type
	 *
	 * @return string
	 */
	public function getPartFormType(): string
	{
		return ChoiceFormPartType::class;
	}

	/**
	 * Get view
	 *
	 * @return string
	 */
	public function getView(): string
	{
		// TODO: Implement getView() method.
		return '';
	}
}