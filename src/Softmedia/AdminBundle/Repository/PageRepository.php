<?php

namespace Softmedia\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Softmedia\AdminBundle\Repository\Behavior\SortableTrait;

class PageRepository extends EntityRepository
{
	use SortableTrait;
}