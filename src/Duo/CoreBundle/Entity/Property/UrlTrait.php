<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\ORM\Mapping as ORM;

trait UrlTrait
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="url", type="string", nullable=true)
	 */
	protected $url;

	/**
	 * {@inheritdoc}
	 */
	public function setUrl(?string $url): UrlInterface
	{
		$this->url = $url;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUrl(): ?string
	{
		return $this->url;
	}
}