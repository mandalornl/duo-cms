<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\FormBundle\Entity\FormPart\SubmitFormPart;

class SubmitFormPartType extends AbstractFormPartType
{
	/**
	 * {@inheritDoc}
	 */
	protected function getDataClass(): string
	{
		return SubmitFormPart::class;
	}
}
