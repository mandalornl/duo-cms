<?php

namespace Duo\PageBundle\Entity;

use Duo\CoreBundle\Entity\DeleteInterface;
use Duo\CoreBundle\Entity\RevisionInterface;
use Duo\CoreBundle\Entity\SortInterface;
use Duo\CoreBundle\Entity\TreeInterface;
use Duo\CoreBundle\Entity\ViewInterface;
use Duo\TaxonomyBundle\Entity\TaxonomyInterface;

interface PageInterface extends ViewInterface,
								DeleteInterface,
								RevisionInterface,
								SortInterface,
								TreeInterface,
								TaxonomyInterface {}