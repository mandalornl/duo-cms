<?php

namespace Duo\MediaBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Duo\BehaviorBundle\Repository\TreeTrait;

class FolderRepository extends EntityRepository
{
	use TreeTrait;
}