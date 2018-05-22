<?php

namespace Duo\TranslatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\TranslationInterface;
use Duo\CoreBundle\Entity\TranslationTrait;

/**
 * @ORM\Table(name="duo_translator_entry_translation")
 * @ORM\Entity()
 */
class EntryTranslation implements TranslationInterface
{
	use TranslationTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="text", type="text", nullable=true)
	 */
	private $text;

	/**
	 * Set text
	 *
	 * @param string $text
	 *
	 * @return EntryTranslation
	 */
	public function setText(string $text = null): EntryTranslation
	{
		$this->text = $text;

		return $this;
	}

	/**
	 * Get text
	 *
	 * @return string
	 */
	public function getText(): ?string
	{
		return $this->text;
	}
}