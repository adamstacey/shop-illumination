<?php
namespace ShopIllumination\AdminBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ShopIllumination\AdminBundle\Entity\Department;

class UpdateProductsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('admin:products:update')
            ->setDescription('Update the products.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $productManager = $this->getContainer()->get('shop_illumination_admin.manager.product');

        $i = 0;
        $batchSize = 20;
        $productCount = 1;

        // Fetch the products
        $products = $em->getRepository("ShopIllumination\\AdminBundle\\Entity\\Product")->findAll();
        $totalNumberOfProducts = count($products);
        foreach ($products as $product)
        {
            $productManager->updateProduct($product);
            $em->persist($product);
            if (($i % $batchSize) === 0)
            {
                $em->flush();
            }
            $percentage = number_format(($productCount / $totalNumberOfProducts) * 100, 1);
            $output->writeln($percentage.'% - Updating Product: '.$product->getId().' ('.$productCount.' of '.$totalNumberOfProducts.')');
            $productCount++;
        }

        $em->flush();

        $output->writeln('Finished!');
    }
}