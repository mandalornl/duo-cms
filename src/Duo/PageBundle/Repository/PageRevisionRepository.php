<?php

namespace Duo\PageBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Duo\CoreBundle\Repository\RevisionInterface;
use Duo\CoreBundle\Repository\RevisionTrait;

class PageRevisionRepository extends EntityRepository implements RevisionInterface
{
	use RevisionTrait;
}
