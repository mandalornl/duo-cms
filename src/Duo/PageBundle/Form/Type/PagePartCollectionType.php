<?php

namespace Duo\PageBundle\Form\Type;

use Duo\PartBundle\Form\Type\AbstractPartCollectionType;

class PagePartCollectionType extends AbstractPartCollectionType
{
	/**
	 * {@inheritDoc}
	 */
	protected function getRouteName(): string
	{
		return 'duo_page_modal_part_prototype';
	}
}
