<?php

namespace Duo\PageBundle\Entity;

use Duo\CoreBundle\Entity\Property\DeleteTrait;
use Duo\CoreBundle\Entity\Property\RevisionTrait;
use Duo\CoreBundle\Entity\Property\SortTrait;
use Duo\CoreBundle\Entity\Property\TreeTrait;
use Duo\NodeBundle\Entity\AbstractNode;
use Duo\TaxonomyBundle\Entity\Property\TaxonomyTrait;

abstract class AbstractPage extends AbstractNode implements PageInterface
{
	use DeleteTrait;
	use RevisionTrait;
	use SortTrait;
	use TreeTrait;
	use TaxonomyTrait;
}