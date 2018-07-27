<?php

namespace Duo\PageBundle\Entity;

use Duo\CoreBundle\Entity\DeleteTrait;
use Duo\CoreBundle\Entity\RevisionTrait;
use Duo\CoreBundle\Entity\SortTrait;
use Duo\CoreBundle\Entity\TreeTrait;
use Duo\NodeBundle\Entity\AbstractNode;
use Duo\TaxonomyBundle\Entity\TaxonomyTrait;

abstract class AbstractPage extends AbstractNode implements PageInterface
{
	use DeleteTrait;
	use RevisionTrait;
	use SortTrait;
	use TreeTrait;
	use TaxonomyTrait;
}