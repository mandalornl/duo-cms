<?php

namespace Duo\PageBundle\Entity;

use Duo\CoreBundle\Entity\Property\DeleteInterface;
use Duo\CoreBundle\Entity\Property\RevisionInterface;
use Duo\CoreBundle\Entity\Property\SortInterface;
use Duo\CoreBundle\Entity\Property\TreeInterface;
use Duo\CoreBundle\Entity\ViewInterface;
use Duo\DraftBundle\Entity\Property\DraftInterface;
use Duo\NodeBundle\Entity\NodeInterface;
use Duo\TaxonomyBundle\Entity\Property\TaxonomyInterface;

interface PageInterface extends NodeInterface,
								ViewInterface,
								DeleteInterface,
								RevisionInterface,
								SortInterface,
								TreeInterface,
								TaxonomyInterface,
								DraftInterface {}