<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\AbstractRevision;

/**
 * @ORM\Table(name="duo_page_revision")
 * @ORM\Entity()
 */
class PageRevision extends AbstractRevision {}