<?php
namespace ShopIllumination\AdminBundle\Entity;

interface DescribableInterface
{
    /**
     * Get the main description of the object
     * @return DescriptionInterface
     */
    function getDescription();

    /**
     * Get all descriptions
     * @return array
     */
    function getDescriptions();
}