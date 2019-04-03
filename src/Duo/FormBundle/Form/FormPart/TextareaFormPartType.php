<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\FormBundle\Entity\FormPart\TextareaFormPart;

class TextareaFormPartType extends AbstractTextFormPartType
{
	/**
	 * {@inheritDoc}
	 */
	protected function getDataClass(): string
	{
		return TextareaFormPart::class;
	}
}
