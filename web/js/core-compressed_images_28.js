$(document).ready(function() {
    $(".lightbox").lightbox();

    $(".etalage").etalage({
        autoplay: false,
        smallthumbs_position: 'left',
        thumb_image_width: 376,
        thumb_image_height: 376,
        source_image_width: 800,
        source_image_height: 800,
        small_thumbs: 3,
        zoom_area_width: 398,
        zoom_area_height: 398,
        smallthumb_hide_single: false
    });

    $(".etalage-inset").etalage({
        autoplay: false,
        smallthumbs_position: 'left',
        thumb_image_width: 238,
        thumb_image_height: 238,
        source_image_width: 600,
        source_image_height: 600,
        small_thumbs: 3,
        zoom_area_width: 260,
        zoom_area_height: 260
    });

    $(document).on("click", ".etalage_magnifier > div > img", function() {
        if ($(".etalage_small_thumbs").length > 0)
        {
            var $images = [];
            var $activeImage = $(".etalage_smallthumb_active");
            $images.push($activeImage.find("img").attr("src"));
            $activeImage.nextAll().each(function() {
                $images.push($(this).find("img").attr("src"));
            });
            $activeImage.prevAll().each(function() {
                $images.push($(this).find("img").attr("src"));
            });
            $.lightbox($images);
        } else {
            $.lightbox($(this).attr("src"));
        }
    });
});