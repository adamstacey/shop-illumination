<?php
namespace ShopIllumination\AdminBundle\Entity;

interface DescriptionInterface
{
    function getId();
    function getPageTitle();
    function getHeader();
    function getDescription();
    function getMetaDescription();
    function getMetaKeywords();
}