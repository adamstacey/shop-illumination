<?php
namespace ShopIllumination\AdminBundle\Command;

use Doctrine\ORM\EntityManager;
use ShopIllumination\AdminBundle\Entity\DepartmentToFeature;
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

class FixTempCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('admin:fix:temp')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getContainer()->get('doctrine')->getManager();

        // Delete all the old products
        $oldProducts = $em->getRepository('ShopIllumination\AdminBundle\Entity\Product')->createQueryBuilder('p')
            ->where('p.brand = 15')
            ->andWhere('p.template = \'maia\'')
            ->getQuery()
            ->execute();
        foreach($oldProducts as $oldProduct)
        {
            \Doctrine\Common\Util\Debug::dump($oldProduct->getId());
            $em->remove($oldProduct);
            $em->flush();
        }
        $output->writeln('Deleted old products');

        $output->writeln('Finished!');
    }
}