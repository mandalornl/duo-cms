<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\PageBundle\Entity\AbstractPage;

/**
 * @ORM\Table(name="duo_page")
 * @ORM\Entity(repositoryClass="Duo\PageBundle\Repository\PageRepository")
 */
class Page extends AbstractPage {}