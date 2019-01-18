<?php

namespace Duo\CoreBundle\Entity\Property;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

trait UuidTrait
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="uuid", type="string", length=36, nullable=false, unique=true, options={ "fixed" = true })
	 */
	protected $uuid;

	/**
	 * Get uuid
	 *
	 * @return string
	 */
	public function getUuid(): ?string
	{
		try
		{
			return $this->uuid = $this->uuid ?: (string)Uuid::uuid4();
		}
		catch (\Exception $e)
		{
			return null;
		}
	}

	/**
	 * On clone uuid
	 */
	protected function onCloneUuid(): void
	{
		$this->uuid = null;
	}
}
