<?php

namespace Duo\PageBundle\Form;

use Duo\PartBundle\Form\AbstractPartCollectionType;

class PagePartCollectionType extends AbstractPartCollectionType
{
	/**
	 * {@inheritdoc}
	 */
	protected function getModalRoute(): string
	{
		return 'duo_page_part_modal_prototype';
	}
}