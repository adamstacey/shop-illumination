<?php

namespace ShopIllumination\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use ShopIllumination\AdminBundle\Form\Department\EditDepartmentDeliveryType;
use ShopIllumination\AdminBundle\Form\Department\EditDepartmentOverviewType;
use ShopIllumination\AdminBundle\Form\Department\EditDepartmentProductTemplatesType;
use ShopIllumination\AdminBundle\Form\Department\EditDepartmentSeoType;
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
use ShopIllumination\AdminBundle\Form\Department\EditDepartmentFeaturesType;
use ShopIllumination\AdminBundle\Entity\Image;
use ShopIllumination\AdminBundle\Entity\Department;
use ShopIllumination\AdminBundle\Entity\Department\Description;
use ShopIllumination\AdminBundle\Manager\DepartmentManager;
use ShopIllumination\AdminBundle\Manager\ProductManager;
use ShopIllumination\AdminBundle\Manager\SeoManager;

/**
 * @Route("/")
 */
class DepartmentController extends Controller
{
    /**
     * @Route("/admin/departments", name="departments_index")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository("ShopIllumination\\AdminBundle\\Entity\\Department");
        $departments = $repo->findAll();


        // parameters to template
        return $this->render('ShopIlluminationAdminBundle:Department:index.html.twig', array('departments' => $departments));
    }

    /**
     * @Route("/admin/departments/new", name="departments_new")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $manager = $this->getManager();

        $department = $manager->createDepartment();

        /**
         * @var \Craue\FormFlowBundle\Form\FormFlow $flow
         */
        $flow = $this->get('shop_illumination_admin.form.flow.new_department');
        $flow->bind($department);

        // Get current form step
        $form = $flow->createForm();

        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            if ($flow->nextStep())
            {
                // Get next form step
                $form = $flow->createForm();
            } else {
                // Update the department path
                $manager->updateDepartmentPath($department);

                // Update the object links
                $manager->updateObjectLinks($department);

                // Update the database
                $em->persist($department);
                $em->flush();

                $flow->reset();

                // Notify user
                $this->get('session')->getFlashBag()->add('notice', 'The new department "'.$department->getName().'" has been added.');

                // Check if request is modal
                if ($request->query->get('modal') == true)
                {
                    // Break out the modal
                    return $this->render('ShopIlluminationAdminBundle:Includes:modalBreakout.html.twig');
                } else {
                    // Forward
                    return $this->redirect($this->get('router')->generate('homepage'));
                }

            }
        }

        return $this->render('ShopIlluminationAdminBundle:Department:new.html.twig', array(
            'form' => $form->createView(),
            'flow' => $flow,
        ));
    }

    public function baseEditAction(Request $request, $departmentId, $template, $formClass)
    {
        $em = $this->getDoctrine()->getManager();

        $manager = $this->getManager();

        $department = $em->getRepository("ShopIllumination\AdminBundle\Entity\Department")->find($departmentId);
        if(!$department)
        {
            throw new NotFoundHttpException("Department not found");
        }

        // Clone features
        $originalFeatures = array();
        foreach($department->getFeatures() as $feature)
        {
            $originalFeatures[] = $feature;
        }

        $form = $this->createForm($formClass, $department);

        if ($request->isMethod('POST'))
        {
            $form->submit($request);
            if ($form->isValid())
            {

                // Update the department path
                $manager->updateDepartmentPath($department);

                // Update the features
                $manager->updateFeatures($originalFeatures, $department);

                // Update the object links
                $manager->updateObjectLinks($department);

                // Update the database
                $em->persist($department);
                $em->flush();

                // Notify user
                $this->get('session')->getFlashBag()->all('notice', 'The department "'.$department->getName().'" has been updated.');

                // Check if request is modal
                if ($request->query->get('modal') == true)
                {
                    // Break out the modal
                    return $this->render('ShopIlluminationAdminBundle:Includes:modalBreakout.html.twig');
                } else {
                    // Forward
                    return $this->redirect($this->generateUrl($request->attributes->get('_route'), array(
                        'departmentId' => $department->getId(),
                    )));
                }
            }
        }

        return $this->render($template, array(
            'department' => $department,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/departments/{departmentId}/edit", name="departments_edit")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function editAction(Request $request, $departmentId)
    {
        return $this->redirect($this->generateUrl('departments_edit_overview', array(
            'departmentId' => $departmentId
        )));
    }

    /**
     * @Route("/admin/departments/{departmentId}/overview", name="departments_edit_overview")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function editOverviewAction(Request $request, $departmentId)
    {
        return $this->baseEditAction($request, $departmentId, 'ShopIlluminationAdminBundle:Department:edit_overview.html.twig', new EditDepartmentOverviewType());
    }

    /**
     * @Route("/admin/departments/{departmentId}/seo", name="departments_edit_seo")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function editSeoAction(Request $request, $departmentId)
    {
        return $this->baseEditAction($request, $departmentId, 'ShopIlluminationAdminBundle:Department:edit_seo.html.twig', new EditDepartmentSeoType());
    }

    /**
     * @Route("/admin/departments/{departmentId}/delivery", name="departments_edit_delivery")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function editDeliveryAction(Request $request, $departmentId)
    {
        return $this->baseEditAction($request, $departmentId, 'ShopIlluminationAdminBundle:Department:edit_delivery.html.twig', new EditDepartmentDeliveryType());
    }

    /**
     * @Route("/admin/departments/{departmentId}/features", name="departments_edit_features")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function editFeaturesAction(Request $request, $departmentId)
    {
        return $this->baseEditAction($request, $departmentId, 'ShopIlluminationAdminBundle:Department:edit_features.html.twig', new EditDepartmentFeaturesType());
    }

    /**
     * @Route("/admin/departments/{departmentId}/products", name="departments_edit_product_templates")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function editProductTemplatesAction(Request $request, $departmentId)
    {
        return $this->baseEditAction($request, $departmentId, 'ShopIlluminationAdminBundle:Department:edit_product_templates.html.twig', new EditDepartmentProductTemplatesType());
    }

    /**
     * @Route("/admin/departments/{departmentId}/delete", name="departments_delete")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(Request $request, $departmentId)
    {
        $em = $this->getDoctrine()->getManager();

        $department = $em->getRepository("ShopIllumination\\AdminBundle\\Entity\\Department")->find($departmentId);
        if(!$department)
        {
            throw new NotFoundHttpException("Department not found");
        }

        $em->remove($department);
        $em->flush();

        return $this->redirect($this->generateUrl('departments_index'));
    }

    /**
     * @Route("/admin/departments/{departmentId}/moveUp", name="departments_moveup")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function moveUpAction(Request $request, $departmentId)
    {
        $manager = $this->getManager();
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("ShopIllumination\\AdminBundle\\Entity\\Department");
        $department = $repository->find($departmentId);
        if (!$department)
        {
            throw new NotFoundHttpException("Department not found");
        }
        $repository->moveUp($department, 1);
        $em->persist($department);
        $em->flush();
        $manager->updateDisplayOrders();
        return $this->redirect($this->generateUrl('departments_index'));
    }

    /**
     * @Route("/admin/departments/{departmentId}/moveDown", name="departments_movedown")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function moveDownAction(Request $request, $departmentId)
    {
        $manager = $this->getManager();
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("ShopIllumination\\AdminBundle\\Entity\\Department");
        $department = $repository->find($departmentId);
        if (!$department)
        {
            throw new NotFoundHttpException("Department not found");
        }
        $repository->moveDown($department, 1);
        $em->persist($department);
        $em->flush();
        $manager->updateDisplayOrders();
        return $this->redirect($this->generateUrl('departments_index'));
    }

    /**
     * @Route("/admin/departments/{departmentId}/rebuildProducts", name="departments_edit_rebuild_products")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function editRebuildProductsAction(Request $request, $departmentId)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '600');
        $productManager = $this->getProductManager();
        $em = $this->getDoctrine()->getManager();
        $updates = array();
        $department = $em->getRepository("ShopIllumination\\AdminBundle\\Entity\\Department")->find($departmentId);
        if ($department)
        {
            $productToDepartments = $em->getRepository("ShopIllumination\\AdminBundle\\Entity\\ProductToDepartment")->findBy(array('department' => $department));
            foreach ($productToDepartments as $productToDepartment)
            {
                $product = $productToDepartment->getProduct();
                if ($product)
                {
                    foreach ($product->getVariants() as $variant)
                    {
                        if ($variant)
                        {
                            $variantDescription = $variant->getDescription();
                            if ($variantDescription)
                            {
                                if (!isset($updates[$variant->getId()]))
                                {
                                    $updates[$variant->getId()] = array();
                                }
                                $updates[$variant->getId()]['productCode'] = $variant->getProductCode();
                                $updates[$variant->getId()]['existingPageTitle'] = $variantDescription->getPageTitle();
                                $updates[$variant->getId()]['existingHeader'] = $variantDescription->getHeader();
                                $updates[$variant->getId()]['existingMetaDescription'] = $variantDescription->getMetaDescription();
                                $updates[$variant->getId()]['existingMetaKeywords'] = $variantDescription->getMetaKeywords();
                                $updates[$variant->getId()]['existingUrl'] = $variant->getUrl();
                            }
                        }
                    }

                    // Update the product
                    $productManager->updateProduct($product);

                    $em->persist($product);
                    $em->flush();

                    foreach ($product->getVariants() as $variant)
                    {
                        if ($variant)
                        {
                            $variantDescription = $variant->getDescription();
                            if ($variantDescription)
                            {
                                if (!isset($updates[$variant->getId()]))
                                {
                                    $updates[$variant->getId()] = array();
                                }
                                $updates[$variant->getId()]['updatedPageTitle'] = $variantDescription->getPageTitle();
                                $updates[$variant->getId()]['updatedHeader'] = $variantDescription->getHeader();
                                $updates[$variant->getId()]['updatedMetaDescription'] = $variantDescription->getMetaDescription();
                                $updates[$variant->getId()]['updatedMetaKeywords'] = $variantDescription->getMetaKeywords();
                                $updates[$variant->getId()]['updatedUrl'] = $variant->getUrl();
                            }
                        }
                    }
                }
            }
        }

        // Parameters to template
        return $this->render('ShopIlluminationAdminBundle:Department:edit_rebuild_products.html.twig', array('department' => $department, 'updates' => $updates));
    }

    /**
     * Fetch project manager from container
     *
     * @return \ShopIllumination\AdminBundle\Manager\DepartmentManager
     */
    private function getManager()
    {
        return $this->get('shop_illumination_admin.manager.department');
    }

    /**
     * Fetch project manager from container
     *
     * @return \ShopIllumination\AdminBundle\Manager\ProductManager
     */
    private function getProductManager()
    {
        return $this->get('shop_illumination_admin.manager.product');
    }
}
