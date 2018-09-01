<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\FormBundle\Entity\FormPart\EmailFormPart;
use Duo\FormBundle\Form\AbstractTextFormPartType;

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