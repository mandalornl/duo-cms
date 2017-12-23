<?php

namespace Duo\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Duo\AdminBundle\Repository\Behavior\SortTrait;

class PageRepository extends EntityRepository
{
	use SortTrait;
}