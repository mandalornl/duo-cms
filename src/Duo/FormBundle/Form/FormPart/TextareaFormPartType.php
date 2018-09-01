<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\FormBundle\Entity\FormPart\TextareaFormPart;
use Duo\FormBundle\Form\AbstractTextFormPartType;

class TextareaFormPartType extends AbstractTextFormPartType
{
	/**
	 * {@inheritdoc}
	 */
	protected function getDataClass(): string
	{
		return TextareaFormPart::class;
	}
}