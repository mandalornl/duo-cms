<?php

namespace Duo\NodeBundle\Entity;

use Duo\CoreBundle\Entity\CloneTrait;
use Duo\CoreBundle\Entity\TranslationTrait;

abstract class AbstractNodeTranslation implements NodeTranslationInterface
{
	use CloneTrait;
	use TranslationTrait;
}