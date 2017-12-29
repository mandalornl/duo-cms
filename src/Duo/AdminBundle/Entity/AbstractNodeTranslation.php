<?php

namespace Duo\AdminBundle\Entity;

use Duo\BehaviorBundle\Entity\TranslationInterface;
use Duo\BehaviorBundle\Entity\TranslationTrait;

abstract class AbstractNodeTranslation implements TranslationInterface
{
	use TranslationTrait;
}