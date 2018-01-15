<?php

namespace Duo\NodeBundle\Entity;

use Duo\BehaviorBundle\Entity\CloneTrait;
use Duo\BehaviorBundle\Entity\TranslationInterface;
use Duo\BehaviorBundle\Entity\TranslationTrait;

abstract class AbstractNodeTranslation implements TranslationInterface
{
	use TranslationTrait;
	use CloneTrait;
}