<?php

namespace Duo\SeoBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Duo\SeoBundle\Entity\Redirect;

class RedirectRepository extends ServiceEntityRepository
{
	/**
	 * RedirectRepository constructor
	 *
	 * @param ManagerRegistry $registry
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Redirect::class);
	}
}