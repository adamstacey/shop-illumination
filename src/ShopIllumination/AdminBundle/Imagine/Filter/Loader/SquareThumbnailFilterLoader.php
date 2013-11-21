<?php
namespace ShopIllumination\AdminBundle\Imagine\Filter\Loader;

use Avalanche\Bundle\ImagineBundle\Imagine\Filter\Loader\LoaderInterface;
use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use ShopIllumination\AdminBundle\Imagine\Filter\SquareThumbnailFilter;

class SquareThumbnailFilterLoader implements LoaderInterface
{
    public function load(array $options = array())
    {
        $mode = $options['mode'] === 'outset' ?
            ManipulatorInterface::THUMBNAIL_OUTBOUND :
            ManipulatorInterface::THUMBNAIL_INSET;
        list($width, $height) = $options['size'];

        return new SquareThumbnailFilter(new Box($width, $height), $mode);
    }
}