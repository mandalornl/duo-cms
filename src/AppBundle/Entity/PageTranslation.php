<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\PageBundle\Entity\AbstractPageTranslation;

/**
 * @ORM\Table(name="duo_page_translation")
 * @ORM\Entity()
 */
class PageTranslation extends AbstractPageTranslation {}