<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\SecurityBundle\Entity\AbstractUser;

/**
 * @ORM\Table(
 *     name="duo_security_user",
 *     indexes={
 *		   @ORM\Index(name="IDX_PASSWORD_TOKEN", columns={ "password_token" })
 *	   }
 * )
 * @ORM\Entity(repositoryClass="Duo\SecurityBundle\Repository\UserRepository")
 */
class User extends AbstractUser {}