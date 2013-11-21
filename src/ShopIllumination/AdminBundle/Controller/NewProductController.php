<?php
namespace ShopIllumination\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use ShopIllumination\AdminBundle\Entity\Product;
use ShopIllumination\AdminBundle\Form\NewProduct\ProductDepartmentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Solarium_Query_Select;
use ShopIllumination\AdminBundle\Manager\ProductManager;
use ShopIllumination\AdminBundle\Manager\SeoManager;
use ShopIllumination\AdminBundle\Form\NewProduct\ProductsType;

class NewProductController extends Controller {
    /**
     * @Route("/admin/newProducts/newProducts", name="new_products_new")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function newProductsAction(Request $request, $departmentId = null, $admin = false)
    {
        $em = $this->getDoctrine()->getManager();

        $manager = $this->getManager();

        $form = $this->createForm(new ProductsType());

        if ($request->isMethod('POST'))
        {
            $form->submit($request);
            if ($form->isValid())
            {
                // Do something: we will get to that!
            }
        }

        return $this->render('ShopIlluminationAdminBundle:NewProduct:newProducts.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Fetch product manager from container
     *
     * @return \ShopIllumination\AdminBundle\Manager\ProductManager
     */
    private function getManager()
    {
        return $this->get('shop_illumination_admin.manager.product');
    }

    /**
     * Fetch image manager from container
     *
     * @return \ShopIllumination\AdminBundle\Manager\ImageManager
     */
    private function getImageManager()
    {
        return $this->get('shop_illumination_admin.manager.image');
    }

    /**
     * Fetch document manager from container
     *
     * @return \ShopIllumination\AdminBundle\Manager\DocumentManager
     */
    private function getDocumentManager()
    {
        return $this->get('shop_illumination_admin.manager.document');
    }
}