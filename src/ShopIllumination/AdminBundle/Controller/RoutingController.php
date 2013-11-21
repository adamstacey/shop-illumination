<?php

namespace ShopIllumination\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/")
 */
class RoutingController extends Controller
{
    /**
     * @Route("/all/{url}.html", name = "routing_all", requirements = {"url": ".+"})
     * @Method({"GET"})
     */
    public function routingAllAction(Request $request, $url)
    {
        return $this->routingAction($request, $url, true);
    }

    /**
     * @Route("/{url}.html", name = "routing", requirements = {"url": ".+"})
     * @Method({"GET"})
     */
    public function routingAction(Request $request, $url, $all=false)
    {
        // Tidy up URL
        $url = trim($url);

        // Try and find the routing
        $routingObject = $this->getDoctrine()->getRepository('ShopIlluminationAdminBundle:Routing')->findOneBy(array('url' => $url, 'locale' => 'en'));
        if (!$routingObject)
        {
            // If no route was found check to see if the user should be redirected
            $redirectObject = $this->getDoctrine()->getRepository('ShopIlluminationAdminBundle:Redirect')->findOneBy(array('redirectFrom' => $url));
            if ($redirectObject)
            {
                return $this->redirect($this->get('router')->generate('routing', array('url' => $redirectObject->getRedirectTo())), $redirectObject->getRedirectCode());
            }
        } else {
            if ($this->isObjectViewable($routingObject))
            {
                // Forward request to relevant controller
                switch (get_class($routingObject))
                {
                    case 'ShopIllumination\AdminBundle\Entity\Brand\Routing':
                        return $this->forward('ShopIlluminationAdminBundle:Listing:index', array('brandId' => $routingObject->getObjectId(), 'all' => $all), $request->query->all());
                        break;
                    case 'ShopIllumination\AdminBundle\Entity\Brand\DepartmentRouting':
                        return $this->forward('ShopIlluminationAdminBundle:Listing:index', array('brandId' => $routingObject->getObjectId(), 'departmentId' => $routingObject->getSecondaryId(), 'all' => $all), $request->query->all());
                        break;
                    case 'ShopIllumination\AdminBundle\Entity\Department\Routing':
                        return $this->forward('ShopIlluminationAdminBundle:Listing:index', array('departmentId' => $routingObject->getObjectId(), 'all' => $all), $request->query->all());
                        break;
                    case 'ShopIllumination\AdminBundle\Entity\Product\Routing':
                        if ($all)
                        {
                            // All flag should be ignored for the product view page
                            $twig = $this->container->get('templating');
                            $content = $twig->render('ShopIlluminationAdminBundle:Includes:error404.html.twig');
                            return new Response($content, 404, array('Content-Type', 'text/html'));
                        }

                        return $this->forward('ShopIlluminationAdminBundle:Product:view', array('id' => $routingObject->getObjectId()), $request->query->all());
                        break;
                    case 'ShopIllumination\AdminBundle\Entity\Product\Variant\Routing':
                        if ($all)
                        {
                            // All flag should be ignored for the product view page
                            $twig = $this->container->get('templating');
                            $content = $twig->render('ShopIlluminationAdminBundle:Includes:error404.html.twig');
                            return new Response($content, 404, array('Content-Type', 'text/html'));
                        }

                        return $this->forward('ShopIlluminationAdminBundle:Product:viewWithVariant', array('id' => $routingObject->getVariant()->getId()), $request->query->all());
                        break;
                }
            }
        }

        // If no route was found return a hard 404 error
        $twig = $this->container->get('templating');
        $content = $twig->render('ShopIlluminationAdminBundle:Includes:error404.html.twig');
        return new Response($content, 404, array('Content-Type', 'text/html'));
    }

    /**
     * Check that the object is not disabled
     * @param $object The object to check
     * @return bool
     */
    private function isObjectViewable($object)
    {
        if($this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            return true;
        }

        // Check if the object is available
        if (method_exists($object, 'getStatus') && $object->getStatus() !== 'a')
        {
            return false;
        }

        return true;
    }
}
