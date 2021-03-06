<?php
namespace ShopIllumination\AdminBundle\Entity\Department;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use \ShopIllumination\AdminBundle\Entity\Routing as SiteRouting;

/**
 * @ORM\Entity
 */
class Routing extends SiteRouting
{
    /**
     * @ORM\ManyToOne(targetEntity="ShopIllumination\AdminBundle\Entity\Department", inversedBy="routings")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id")
     */
    protected $department;

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
        return 'department';
    }
}