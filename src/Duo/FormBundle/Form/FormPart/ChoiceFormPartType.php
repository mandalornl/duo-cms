<?php

namespace Duo\FormBundle\Form\FormPart;

use Duo\FormBundle\Entity\FormPart\ChoiceFormPart;
use Duo\FormBundle\Form\AbstractChoiceFormPartType;

class ChoiceFormPartType extends AbstractChoiceFormPartType
{
	/**
	 * {@inheritdoc}
	 */
	protected function getDataClass(): string
	{
		return ChoiceFormPart::class;
	}
}