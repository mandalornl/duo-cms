<?php

namespace Duo\FormBundle\Form;

use Duo\PartBundle\Form\AbstractPartCollectionType;

class FormPartCollectionType extends AbstractPartCollectionType
{
	/**
	 * {@inheritdoc}
	 */
	protected function getRouteName(): string
	{
		return 'duo_form_part_modal_prototype';
	}
}