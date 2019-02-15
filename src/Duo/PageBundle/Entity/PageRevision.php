<?php

namespace Duo\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\AbstractRevision;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(
 *     name="duo_page_revision",
 *     uniqueConstraints={
 *		   @ORM\UniqueConstraint(name="UNIQ_NAME", columns={ "name" })
 *	   }
 * )
 * @ORM\Entity(repositoryClass="Duo\PageBundle\Repository\PageRevisionRepository")
 * @UniqueEntity(fields={ "name" }, message="duo_page.errors.name_used")
 */
class PageRevision extends AbstractRevision {}
