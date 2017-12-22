<?php

namespace Duo\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Duo\AdminBundle\Repository\Behavior\SortableTrait;

class PageRepository extends EntityRepository
{
	use SortableTrait;
}