<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\DraftBundle\Entity\AbstractDraft;

/**
 * @ORM\Table(name="duo_page_draft")
 * @ORM\Entity()
 */
class PageDraft extends AbstractDraft
{

}