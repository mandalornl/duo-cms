<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\FormBundle\Entity\FormPart\EmailFormPart;

class EmailFormPartType extends AbstractTextFormPartType
{
	/**
	 * {@inheritdoc}
	 */
	protected function getDataClass(): string
	{
		return EmailFormPart::class;
	}
}