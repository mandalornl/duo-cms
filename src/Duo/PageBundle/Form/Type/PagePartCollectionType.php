<?php

namespace Duo\PageBundle\Form\Type;

use Duo\PartBundle\Form\Type\AbstractPartCollectionType;

class PagePartCollectionType extends AbstractPartCollectionType
{
	/**
	 * {@inheritdoc}
	 */
	protected function getRouteName(): string
	{
		return 'duo_page_part_modal_prototype';
	}
}