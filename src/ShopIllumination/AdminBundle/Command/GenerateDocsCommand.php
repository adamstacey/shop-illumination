<?php
namespace ShopIllumination\AdminBundle\Command;

use Doctrine\ORM\EntityManager;
use ShopIllumination\AdminBundle\Entity\DepartmentToFeature;
use ShopIllumination\AdminBundle\Entity\Order;
use ShopIllumination\AdminBundle\Entity\Product;
use ShopIllumination\AdminBundle\Entity\Product\Variant;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ShopIllumination\AdminBundle\Entity\Department;
use ShopIllumination\AdminBundle\Entity\DepartmentTmp;
use ShopIllumination\AdminBundle\Indexer\ProductIndexer;
use ShopIllumination\AdminBundle\Repository\DepartmentRepository;

class GenerateDocsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('admin:generate:docs')
            ->addArgument('start', InputArgument::OPTIONAL, 0)
            ->addArgument('batchSize', InputArgument::OPTIONAL, 1000);
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var $em EntityManager
         * @var $order Order
         */
        $em = $this->getContainer()->get('doctrine')->getManager();        // Get the services
        $systemService = $this->getContainer()->get('web_illumination_admin.system_service');
        $orderService = $this->getContainer()->get('web_illumination_admin.order_service');
        $pdfUrl = $this->getContainer()->getParameter('cdn_host');

        // Load orders
        $query = $em->createQuery("SELECT o FROM ShopIllumination\\AdminBundle\\Entity\\Order o");
        $query->setFirstResult($input->getArgument('start'));
        $query->setMaxResults($input->getArgument('batchSize'));
        $i = $input->getArgument('start');

        $iterableResult = $query->iterate();

        try {
            while (($row = $iterableResult->next()) !== false) {
                $order = $row[0];

                // Create the PDF documents
                $orderDocument = $orderService->getUploadRootDir().'/order-'.$order->getId().'.pdf';
                $copyOrderDocument = $orderService->getUploadRootDir().'/copy-order-'.$order->getId().'.pdf';
                $invoiceDocument = $orderService->getUploadRootDir().'/invoice-'.$order->getId().'.pdf';
                $deliveryNoteDocument = $orderService->getUploadRootDir().'/delivery-note-'.$order->getId().'.pdf';

                if(!file_exists($orderDocument))
                {
                    $systemService->pipeExec('xvfb-run -a -s "-screen 0 640x480x16" /usr/bin/wkhtmltopdf '.$pdfUrl.'/admin/orders/viewOrder/'.$order->getId().' '.$orderDocument.' 2>&1');
                    $systemService->pipeExec('xvfb-run -a -s "-screen 0 640x480x16" /usr/bin/wkhtmltopdf '.$pdfUrl.'/admin/orders/viewInvoice/'.$order->getId().' '.$invoiceDocument.' 2>&1');
                    $systemService->pipeExec('xvfb-run -a -s "-screen 0 640x480x16" /usr/bin/wkhtmltopdf '.$pdfUrl.'/admin/orders/viewCopyOrder/'.$order->getId().' '.$copyOrderDocument.' 2>&1');
                    $systemService->pipeExec('xvfb-run -a -s "-screen 0 640x480x16" /usr/bin/wkhtmltopdf '.$pdfUrl.'/admin/orders/viewDeliveryNote/'.$order->getId().' '.$deliveryNoteDocument.' 2>&1');
                }

                $em->detach($row[0]);
                $output->writeln($i);
                $i++;
            }
        } catch (\Exception $e) {
            $output->writeln("An error occurred");
        }

        $output->writeln('Finished!');
    }
}