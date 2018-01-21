<?php

namespace Duo\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\PartBundle\Entity\AbstractPartReference;

/**
 * @ORM\Table(
 *     name="page_part_reference",
 *     indexes={
 *		   @ORM\Index(name="entity_idx", columns={ "entity_id" }),
 *		   @ORM\Index(name="part_idx", columns={ "part_id", "part_class" })
 *	   }
 * )
 * @ORM\Entity(repositoryClass="Duo\PageBundle\Repository\PagePartReferenceRepository")
 */
class PagePartReference extends AbstractPartReference
{

}