<?php

namespace ShopIllumination\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ShopIlluminationUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
