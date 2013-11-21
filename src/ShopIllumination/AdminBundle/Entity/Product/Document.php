<?php
namespace ShopIllumination\AdminBundle\Entity\Product;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use \ShopIllumination\AdminBundle\Entity\Document as SiteDocument;

/**
 * @ORM\Entity
 */
class Document extends SiteDocument
{
    /**
     * @ORM\ManyToOne(targetEntity="ShopIllumination\AdminBundle\Entity\Product", inversedBy="documents")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * Set product
     *
     * @param \ShopIllumination\AdminBundle\Entity\Product $product
     * @return Routing
     */
    public function setProduct(\ShopIllumination\AdminBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \ShopIllumination\AdminBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    public function getObjectType()
    {
        return 'product';
    }
}