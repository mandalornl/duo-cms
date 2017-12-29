<?php

namespace Duo\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Duo\BehaviorBundle\Repository\SortTrait;

class PageRepository extends EntityRepository
{
	use SortTrait;
}