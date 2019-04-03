<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\FormBundle\Entity\FormPart\ChoiceFormPart;

class ChoiceFormPartType extends AbstractChoiceFormPartType
{
	/**
	 * {@inheritDoc}
	 */
	protected function getDataClass(): string
	{
		return ChoiceFormPart::class;
	}
}
