<?php

namespace Duo\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\PartBundle\Entity\AbstractPart;
use Duo\PartBundle\Entity\Property\PartInterface;

abstract class AbstractPagePart extends AbstractPart implements PagePartInterface
{
	/**
	 * @var PartInterface
	 *
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PageTranslation")
	 * @ORM\JoinColumn(name="entity_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $entity;
}