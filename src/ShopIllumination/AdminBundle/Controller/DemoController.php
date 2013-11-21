<?php

namespace ShopIllumination\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/demo")
 */
class DemoController extends Controller
{
    /**
     * @Route("/", name="demo_index")
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {

    }

    /**
     * @Route("/gridsAndPortlets", name="demo_grids_and_portlets")
     * @Method({"GET"})
     * @Template()
     */
    public function gridsAndPortletsAction(Request $request)
    {

    }

    /**
     * @Route("/forms", name="demo_forms")
     * @Method({"GET"})
     * @Template()
     */
    public function formsAction(Request $request)
    {

    }

    /**
     * @Route("/notifications", name="demo_notifications")
     * @Method({"GET"})
     * @Template()
     */
    public function notificationsAction(Request $request)
    {

    }

    /**
     * @Route("/accordions", name="demo_accordions")
     * @Method({"GET"})
     * @Template()
     */
    public function accordionsAction(Request $request)
    {

    }

    /**
     * @Route("/tabs", name="demo_tabs")
     * @Method({"GET"})
     * @Template()
     */
    public function tabsAction(Request $request)
    {

    }

    /**
     * @Route("/product", name="demo_product")
     * @Method({"GET"})
     * @Template()
     */
    public function productAction(Request $request)
    {

    }

    /**
     * @Route("/icons", name="demo_icons")
     * @Method({"GET"})
     * @Template()
     */
    public function iconsAction(Request $request)
    {

    }

    /**
     * @Route("/buttons", name="demo_buttons")
     * @Method({"GET"})
     * @Template()
     */
    public function buttonsAction(Request $request)
    {

    }

    /**
     * @Route("/upload", name="demo_upload")
     * @Method({"GET"})
     * @Template()
     */
    public function uploadAction(Request $request)
    {

    }

    /**
     * @Route("/image/{id}", name="demo_image")
     * @Method({"GET"})
     * @Template()
     */
    public function imageAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $image = $em->getRepository("ShopIllumination\AdminBundle\Entity\Image")->find($id);
        if(!$image)
        {
            throw new NotFoundHttpException("Image not found");
        }

        return array(
            'image' => $image,
        );
    }

    /**
     * @Route("/test", name="demo_test")
     * @Method({"GET"})
     */
    public function testAction(Request $request)
    {
        $api = $this->get('shop_illumination_admin.google.google');
        //\Doctrine\Common\Util\Debug::dump($api->findMoreExpensiveProducts('SMS40C02GB', '280', 5)["items"], 5);
        //die();
    }
}
