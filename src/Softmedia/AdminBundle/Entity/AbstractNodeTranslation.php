<?php

namespace Softmedia\AdminBundle\Entity;

use Softmedia\AdminBundle\Entity\Behavior\TranslationInterface;
use Softmedia\AdminBundle\Entity\Behavior\TranslationTrait;

abstract class AbstractNodeTranslation implements TranslationInterface
{
	use TranslationTrait;
}