<?php
namespace ShopIllumination\AdminBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="object_type", type="string")
 * @ORM\DiscriminatorMap({
 *     "product" = "ShopIllumination\AdminBundle\Entity\Product\Routing",
 *     "product_with_feature" = "ShopIllumination\AdminBundle\Entity\Product\FeatureRouting",
 *     "product_variant" = "ShopIllumination\AdminBundle\Entity\Product\Variant\Routing",
 *     "department" = "ShopIllumination\AdminBundle\Entity\Department\Routing",
 *     "brand" = "ShopIllumination\AdminBundle\Entity\Brand\Routing",
 *     "brand_with_department" = "ShopIllumination\AdminBundle\Entity\Brand\DepartmentRouting"
 * })
 * @ORM\Table(name="routing")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"url"}, groups={"Default", "flow_site_new_department_step2", "site_edit_variant_seo", "flow_site_new_product_step9"})
 */
class Routing
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;    
	
	/**
     * @ORM\Column(name="object_id", type="integer", length=11, nullable=true)
     */
    private $objectId;

	/**
     * @ORM\Column(name="secondary_id", type="integer", length=11, nullable=true)
     */
    private $secondaryId;
        
    /**
     * @ORM\Column(name="locale", type="string", length=5)
     */
    private $locale = "en";
    
	/**
     * @ORM\Column(name="url", type="string", length=255, unique=true)
     * @Assert\NotBlank(groups={"flow_site_new_department_step2"}, message="Enter an internal web address.")
     */
    private $url;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set objectId
     *
     * @param integer $objectId
     * @return Routing
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
    
        return $this;
    }

    /**
     * Get objectId
     *
     * @return integer 
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set secondaryId
     *
     * @param integer $secondaryId
     * @return Routing
     */
    public function setSecondaryId($secondaryId)
    {
        $this->secondaryId = $secondaryId;

        return $this;
    }

    /**
     * Get secondaryId
     *
     * @return integer
     */
    public function getSecondaryId()
    {
        return $this->secondaryId;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return Routing
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    
        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Routing
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Routing
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Routing
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}