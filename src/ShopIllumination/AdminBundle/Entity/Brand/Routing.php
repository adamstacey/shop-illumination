<?php
namespace ShopIllumination\AdminBundle\Entity\Brand;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use \ShopIllumination\AdminBundle\Entity\Routing as SiteRouting;

/**
 * @ORM\Entity
 */
class Routing extends SiteRouting
{
    /**
     * @ORM\ManyToOne(targetEntity="ShopIllumination\AdminBundle\Entity\Brand", inversedBy="routings")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id")
     */
    protected $brand;

    /**
     * Set brand
     *
     * @param \ShopIllumination\AdminBundle\Entity\Brand $brand
     * @return Routing
     */
    public function setBrand(\ShopIllumination\AdminBundle\Entity\Brand $brand = null)
    {
        $this->brand = $brand;
    
        return $this;
    }

    /**
     * Get brand
     *
     * @return \ShopIllumination\AdminBundle\Entity\Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    public function getObjectType()
    {
        return 'brand';
    }
}