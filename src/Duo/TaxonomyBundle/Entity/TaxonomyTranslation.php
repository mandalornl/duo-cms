<?php

namespace Duo\TaxonomyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\TranslationInterface;
use Duo\CoreBundle\Entity\TranslationTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="duo_taxonomy_translation",
 *	   uniqueConstraints={
 *	       @ORM\UniqueConstraint(name="name_uniq", columns={ "name", "locale" })
 *	   },
 *     indexes={
 *		   @ORM\Index(name="name_idx", columns={ "name" })
 *	   }
 * )
 * @ORM\Entity()
 * @UniqueEntity(fields={ "name", "locale" }, message="due.taxonomy.errors.taxonomy_used")
 */
class TaxonomyTranslation implements TranslationInterface
{
	use TranslationTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=true)
	 * @Assert\NotBlank()
	 */
	private $name;

	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return TaxonomyTranslation
	 */
	public function setName(string $name = null): TaxonomyTranslation
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName(): ?string
	{
		return $this->name;
	}
}