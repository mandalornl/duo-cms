<?php

namespace Duo\FormBundle\Entity\FormPart;

use Doctrine\ORM\Mapping as ORM;
use Duo\FormBundle\Entity\AbstractFormPart;
use Duo\FormBundle\Form\FormPart\TermsFormPartType;
use Duo\FormBundle\Form\TermsType;
use Duo\PageBundle\Entity\PageInterface;
use Symfony\Component\Validator\Constraints\IsTrue;

/**
 * @ORM\Table(name="duo_form_part_terms")
 * @ORM\Entity()
 */
class TermsFormPart extends AbstractFormPart
{
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="required", type="boolean", options={ "default" = 0})
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
	 * @return TermsFormPart
	 */
	public function setRequired(bool $required = false): TermsFormPart
	{
		$this->required = $required;

		return $this;
	}

	/**
	 * Get required
	 *
	 * @return bool
	 */
	public function getRequired(): bool
	{
		return $this->required;
	}

	/**
	 * Set errorMessage
	 *
	 * @param string $errorMessage
	 *
	 * @return TermsFormPart
	 */
	public function setErrorMessage(string $errorMessage = null): TermsFormPart
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
	 * @return TermsFormPart
	 */
	public function setPage(PageInterface $page = null): TermsFormPart
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
		return TermsType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormOptions(): array
	{
		return [
			'constraints' => $this->getRequired() ? [
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
		return TermsFormPartType::class;
	}
}