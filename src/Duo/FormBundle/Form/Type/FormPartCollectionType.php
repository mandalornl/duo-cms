<?php

namespace Duo\FormBundle\Form\Type;

use Duo\PartBundle\Form\Type\AbstractPartCollectionType;

class FormPartCollectionType extends AbstractPartCollectionType
{
	/**
	 * {@inheritDoc}
	 */
	protected function getRouteName(): string
	{
		return 'duo_form_modal_part_prototype';
	}
}
