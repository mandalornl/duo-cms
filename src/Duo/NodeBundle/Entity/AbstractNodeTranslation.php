<?php

namespace Duo\NodeBundle\Entity;

use Duo\CoreBundle\Entity\CloneTrait;
use Duo\CoreBundle\Entity\TranslationInterface;
use Duo\CoreBundle\Entity\TranslationTrait;

abstract class AbstractNodeTranslation implements TranslationInterface
{
	use CloneTrait;
	use TranslationTrait;
}