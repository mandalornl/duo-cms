<?php

namespace Softmedia\AdminBundle\Entity\Behavior;

interface SluggableInterface
{
    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return SluggableInterface
     */
    public function setSlug(string $slug = null): SluggableInterface;

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