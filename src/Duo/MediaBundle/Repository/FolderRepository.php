<?php

namespace Duo\MediaBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Duo\CoreBundle\Repository\TreeTrait;

class FolderRepository extends EntityRepository
{
	use TreeTrait;
}