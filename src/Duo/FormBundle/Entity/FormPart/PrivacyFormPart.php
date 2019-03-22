<?php

namespace Duo\FormBundle\Entity\FormPart;

use Doctrine\ORM\Mapping as ORM;
use Duo\FormBundle\Form\FormPart\PrivacyFormPartType;
use Duo\FormBundle\Form\Type\PrivacyType;
use Duo\PageBundle\Entity\PageInterface;
use Symfony\Component\Validator\Constraints\IsTrue;

/**
 * @ORM\Table(name="duo_form_part_privacy")
 * @ORM\Entity()
 */
class PrivacyFormPart extends AbstractFormPart
{
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="required", type="boolean", options={ "default" = 0 })
	 */
	private $required = false;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="error_message", type="string", nullable=true)
	 */
	private $errorMessage = false;

	/**
	 * @var PageInterface
	 *
	 * @ORM\ManyToOne(targetEntity="Duo\PageBundle\Entity\PageInterface")
	 * @ORM\JoinColumn(name="page_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	private $page;

	/**
	 * Set required
	 *
	 * @param bool $required
	 *
	 * @return PrivacyFormPart
	 */
	public function setRequired(bool $required): PrivacyFormPart
	{
		$this->required = $required;

		return $this;
	}

	/**
	 * Is required
	 *
	 * @return bool
	 */
	public function isRequired(): bool
	{
		return $this->required;
	}

	/**
	 * Set errorMessage
	 *
	 * @param string $errorMessage
	 *
	 * @return PrivacyFormPart
	 */
	public function setErrorMessage(?string $errorMessage): PrivacyFormPart
	{
		$this->errorMessage = $errorMessage;

		return $this;
	}

	/**
	 * Get errorMessage
	 *
	 * @return string
	 */
	public function getErrorMessage(): ?string
	{
		return $this->errorMessage;
	}

	/**
	 * Set page
	 *
	 * @param PageInterface $page
	 *
	 * @return PrivacyFormPart
	 */
	public function setPage(?PageInterface $page): PrivacyFormPart
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
	 */
	public function getFormType(): string
	{
		return PrivacyType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormOptions(): array
	{
		return [
			'constraints' => $this->isRequired() ? [
				$this->getErrorMessage() ? new IsTrue([
					'message' => $this->getErrorMessage()
				]) : new IsTrue()
			]: null,
			'page' => $this->getPage()
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPartFormType(): string
	{
		return PrivacyFormPartType::class;
	}
}
