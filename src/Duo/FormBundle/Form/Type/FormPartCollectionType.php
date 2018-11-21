<?php

namespace Duo\FormBundle\Form\Type;

use Duo\PartBundle\Form\Type\AbstractPartCollectionType;

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