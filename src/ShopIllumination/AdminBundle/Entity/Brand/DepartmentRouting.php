<?php
namespace ShopIllumination\AdminBundle\Entity\Brand;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use \ShopIllumination\AdminBundle\Entity\Routing as SiteRouting;

/**
 * @ORM\Entity
 */
class DepartmentRouting extends SiteRouting
{
    /**
     * @ORM\ManyToOne(targetEntity="ShopIllumination\AdminBundle\Entity\Brand", inversedBy="routings")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id")
     */
    protected $brand;
    /**
     * @ORM\ManyToOne(targetEntity="ShopIllumination\AdminBundle\Entity\Department", inversedBy="routings")
     * @ORM\JoinColumn(name="secondary_id", referencedColumnName="id")
     */
    protected $department;

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

    /**
     * Set department
     *
     * @param \ShopIllumination\AdminBundle\Entity\Department $department
     * @return Routing
     */
    public function setDepartment(\ShopIllumination\AdminBundle\Entity\Department $department = null)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return \ShopIllumination\AdminBundle\Entity\Department
     */
    public function getDepartment()
    {
        return $this->department;
    }

    public function getObjectType()
    {
        return 'brand_with_department';
    }
}