<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\SecurityBundle\Entity\AbstractUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(
 *     name="duo_user",
 *     uniqueConstraints={
 *	       @ORM\UniqueConstraint(name="username_uniq", columns={ "username" }),
 *     	   @ORM\UniqueConstraint(name="email_uniq", columns={ "email" })
 *	   },
 *     indexes={
 *		   @ORM\Index(name="username_idx", columns={ "username" }),
 *     	   @ORM\Index(name="email_idx", columns={ "email" }),
 *     	   @ORM\Index(name="password_token_idx", columns={ "password_token" })
 *	   }
 * )
 * @ORM\Entity(repositoryClass="Duo\SecurityBundle\Repository\UserRepository")
 * @UniqueEntity(fields={ "username" }, message="duo.security.errors.username_used")
 * @UniqueEntity(fields={ "email" }, message="duo.security.errors.email_used")
 */
class User extends AbstractUser {}