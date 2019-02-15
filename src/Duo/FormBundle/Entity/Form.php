<?php

namespace Duo\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\CloneTrait;
use Duo\CoreBundle\Entity\DuplicateInterface;
use Duo\CoreBundle\Entity\Property\IdInterface;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\TimestampInterface;
use Duo\CoreBundle\Entity\Property\TimestampTrait;
use Duo\CoreBundle\Entity\Property\TranslateInterface;
use Duo\CoreBundle\Entity\Property\TranslateTrait;
use Duo\CoreBundle\Entity\Property\TranslationInterface;
use Duo\CoreBundle\Entity\Property\UuidInterface;
use Duo\CoreBundle\Entity\Property\UuidTrait;
use Duo\CoreBundle\Entity\Property\VersionInterface;
use Duo\CoreBundle\Entity\Property\VersionTrait;
use Duo\PageBundle\Entity\PageInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="duo_form")
 * @ORM\Entity(repositoryClass="Duo\FormBundle\Repository\FormRepository")
 * @UniqueEntity(fields={ "name" }, message="duo_form.errors.name_used")
 */
class Form implements IdInterface,
					  UuidInterface,
					  DuplicateInterface,
					  TimestampInterface,
					  TranslateInterface,
					  VersionInterface
{
	use IdTrait;
	use UuidTrait;
	use TimestampTrait;
	use TranslateTrait;
	use VersionTrait;
	use CloneTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=true, unique=true)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email_from", type="string", nullable=true)
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
	private $emailFrom;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email_to", type="string", nullable=true)
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
	private $emailTo;

	/**
	 * @var bool
	 *
	 * @ORM\Column(name="keep_submissions", type="boolean", options={ "default" = 0 })
	 */
	private $keepSubmissions = false;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="send_submissions_to", type="string", nullable=true)
	 * @Assert\Length(min="0")
	 * @Assert\Email()
	 */
	private $sendSubmissionsTo;

	/**
	 * @var PageInterface
	 *
	 * @ORM\ManyToOne(targetEntity="Duo\PageBundle\Entity\PageInterface")
	 * @ORM\JoinColumn(name="page_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	private $page;

	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return Form
	 */
	public function setName(?string $name): Form
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

	/**
	 * Set emailFrom
	 *
	 * @param string $emailFrom
	 *
	 * @return Form
	 */
	public function setEmailFrom(?string $emailFrom): Form
	{
		$this->emailFrom = $emailFrom;

		return $this;
	}

	/**
	 * Get emailFrom
	 *
	 * @return string
	 */
	public function getEmailFrom(): ?string
	{
		return $this->emailFrom;
	}

	/**
	 * Set emailTo
	 *
	 * @param string $emailTo
	 *
	 * @return Form
	 */
	public function setEmailTo(?string $emailTo): Form
	{
		$this->emailTo = $emailTo;

		return $this;
	}

	/**
	 * Get emailTo
	 *
	 * @return string
	 */
	public function getEmailTo(): ?string
	{
		return $this->emailTo;
	}

	/**
	 * Set keepSubmissions
	 *
	 * @param bool $keepSubmissions
	 *
	 * @return Form
	 */
	public function setKeepSubmissions(bool $keepSubmissions): Form
	{
		$this->keepSubmissions = $keepSubmissions;

		return $this;
	}

	/**
	 * Get keepSubmissions
	 *
	 * @return bool
	 */
	public function getKeepSubmissions(): bool
	{
		return $this->keepSubmissions;
	}

	/**
	 * Set sendSubmissionsTo
	 *
	 * @param string $sendSubmissionsTo
	 *
	 * @return Form
	 */
	public function setSendSubmissionsTo(?string $sendSubmissionsTo): Form
	{
		$this->sendSubmissionsTo = $sendSubmissionsTo;

		return $this;
	}

	/**
	 * Get sendSubmissionsTo
	 *
	 * @return string
	 */
	public function getSendSubmissionsTo(): ?string
	{
		return $this->sendSubmissionsTo;
	}

	/**
	 * Set page
	 *
	 * @param PageInterface $page
	 *
	 * @return Form
	 */
	public function setPage(?PageInterface $page): Form
	{
		$this->page = $page;

		return $this;
	}

	/**
	 * Get page
	 *
	 * @return PageInterface
	 */
	public function getPage(): ?PageInterface
	{
		return $this->page;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return FormTranslation|TranslationInterface
	 */
	public function translate(string $locale = null, bool $fallback = true): FormTranslation
	{
		return $this->doTranslate($locale, $fallback);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString(): string
	{
		return $this->name;
	}

	/**
	 * On clone name
	 */
	protected function onCloneName(): void
	{
		// ensure unique name
		$this->name = ltrim(uniqid("{$this->name}-"), '-');
	}
}
