<?php
namespace ShopIllumination\AdminBundle\Manager;

use ShopIllumination\AdminBundle\Entity\Product;

class RoutingManager extends Manager
{
    public function __construct($doctrine)
    {
        parent::__construct($doctrine);
    }

    public function getClassName($objectType)
    {
        switch ($objectType)
        {
            case 'brand':
                return "ShopIllumination\\AdminBundle\\Entity\\Brand\\Routing";
            case 'brand_with_department':
                return "ShopIllumination\\AdminBundle\\Entity\\Brand\\DepartmentRouting";
            case 'department':
                return "ShopIllumination\\AdminBundle\\Entity\\Department\\Routing";
            case 'product_variant':
                return "ShopIllumination\\AdminBundle\\Entity\\Product\\Variant\\Routing";
            case 'product':
                return "ShopIllumination\\AdminBundle\\Entity\\Product\\Routing";
            default:
                return false;
        }
    }

    public function getObject($route)
    {
        switch (get_class($route))
        {
            case 'ShopIllumination\\AdminBundle\\Entity\\Brand\\Routing':
                return $route->getBrand();
            case 'ShopIllumination\\AdminBundle\\Entity\\Brand\\DepartmentRouting':
                return $route->getBrand();
            case 'ShopIllumination\\AdminBundle\\Entity\\Department\\Routing':
                return $route->getDepartment();
            case 'ShopIllumination\\AdminBundle\\Entity\\Product\\Variant\\Routing':
                return $route->getVariant();
            case 'ShopIllumination\\AdminBundle\\Entity\\Product\\Routing':
                return $route->getProduct();
            default:
                return null;
        }
    }
}