<?php

namespace Duo\CoreBundle\Entity\Property;

interface SlugInterface
{
    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return SlugInterface
     */
    public function setSlug(?string $slug): SlugInterface;

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug(): ?string;

    /**
     * Get value to slugify
     *
     * @return string
     */
    public function getValueToSlugify(): string;
}