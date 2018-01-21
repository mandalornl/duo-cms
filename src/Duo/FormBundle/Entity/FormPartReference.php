<?php

namespace Duo\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\PartBundle\Entity\AbstractPartReference;

/**
 * @ORM\Table(
 *     name="form_part_reference",
 *     indexes={
 *		   @ORM\Index(name="entity_idx", columns={ "entity_id" }),
 *		   @ORM\Index(name="part_idx", columns={ "part_id", "part_class" })
 *	   }
 * )
 * @ORM\Entity(repositoryClass="Duo\FormBundle\Repository\FormPartReferenceRepository")
 */
class FormPartReference extends AbstractPartReference
{

}