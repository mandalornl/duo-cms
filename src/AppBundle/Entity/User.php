<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\SecurityBundle\Entity\AbstractUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(
 *     name="duo_user",
 *     uniqueConstraints={
 *	       @ORM\UniqueConstraint(name="UNIQ_USERNAME", columns={ "username" }),
 *     	   @ORM\UniqueConstraint(name="UNIQ_EMAIL", columns={ "email" })
 *	   },
 *     indexes={
 *		   @ORM\Index(name="IDX_PASSWORD_TOKEN", columns={ "password_token" })
 *	   }
 * )
 * @ORM\Entity(repositoryClass="Duo\SecurityBundle\Repository\UserRepository")
 * @UniqueEntity(fields={ "username" }, message="duo.security.errors.username_used")
 * @UniqueEntity(fields={ "email" }, message="duo.security.errors.email_used")
 */
class User extends AbstractUser {}