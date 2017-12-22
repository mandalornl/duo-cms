<?php

namespace Duo\AdminBundle\Entity;

use Duo\AdminBundle\Entity\Behavior\TranslationInterface;
use Duo\AdminBundle\Entity\Behavior\TranslationTrait;

abstract class AbstractNodeTranslation implements TranslationInterface
{
	use TranslationTrait;
}