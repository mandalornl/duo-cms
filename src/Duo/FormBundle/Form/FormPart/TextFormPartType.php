<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\FormBundle\Entity\FormPart\TextFormPart;

class TextFormPartType extends AbstractTextFormPartType
{
	/**
	 * {@inheritDoc}
	 */
	protected function getDataClass(): string
	{
		return TextFormPart::class;
	}
}
