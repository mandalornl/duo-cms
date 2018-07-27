<?php

namespace Duo\FormBundle\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Duo\AdminBundle\Repository\AbstractEntityRepository;
use Duo\FormBundle\Entity\Form;

class FormRepository extends AbstractEntityRepository
{
	/**
	 * FormRepository constructor
	 *
	 * @param ManagerRegistry $registry
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Form::class);
	}
}