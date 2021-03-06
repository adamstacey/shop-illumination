<?php
namespace ShopIllumination\AdminBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ShopIllumination\AdminBundle\Entity\Department;
use ShopIllumination\AdminBundle\Entity\DepartmentTmp;
use ShopIllumination\AdminBundle\Indexer\ProductIndexer;
use ShopIllumination\AdminBundle\Repository\DepartmentRepository;

class FixDepartmentCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('admin:fix:departments')
            ->setDescription('Fix departments structure')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var $em EntityManager
         * @var $repository DepartmentRepository
         */
        $em = $this->getContainer()->get('doctrine')->getManager();

        //$em->getConnection()->exec("ALTER TABLE departments ADD lft INT NOT NULL, ADD lvl INT NOT NULL, ADD rgt INT NOT NULL, ADD root INT DEFAULT NULL");
        $em->getConnection()->exec("DROP TABLE IF EXISTS departments_tmp");
        $em->getConnection()->exec("CREATE TABLE departments_tmp LIKE departments;");
        $em->getConnection()->exec("ALTER TABLE  `departments_tmp` CHANGE  `id`  `id` INT( 11 ) NOT NULL");

        $repository = $em->getRepository("ShopIllumination\AdminBundle\Entity\Department");
        $departments = $em->createQuery("SELECT d FROM ShopIllumination\AdminBundle\Entity\Department d WHERE d.parent IS NULL ORDER BY d.displayOrder ASC")->execute();

        // Find next ID
        $lastId = $em->createQuery("SELECT d.id FROM ShopIllumination\AdminBundle\Entity\Department d ORDER BY d.id DESC")->setMaxResults(1)->getSingleScalarResult();

        // Create root department
        $rootDepartment = new DepartmentTmp();
        $rootDepartment->setId($lastId + 1);
        $rootDepartment->setStatus('h');
        $rootDepartment->setParent(null);
        $rootDepartment->setDepartmentPath('');
        $rootDepartment->setTemplate('');
        $rootDepartment->setHidePrices(false);
        $rootDepartment->setShowPricesOutOfHours(false);
        $rootDepartment->setDeliveryBand(1);
        $rootDepartment->setInheritedDeliveryBand(1);
        $rootDepartment->setDisplayOrder(0);
        $rootDepartment->setCreatedAt(new \DateTime());
        $rootDepartment->setUpdatedAt(new \DateTime());

        $em->persist($rootDepartment);
        $em->flush();

        foreach($departments as $department) {
            $newDepartment = $this->createTempDepartment($department, $rootDepartment);
            $em->persist($newDepartment);
            $this->persistChildren($newDepartment, $department, $department->getChildren());
        }

        $em->flush();

        $em->getConnection()->exec("SET FOREIGN_KEY_CHECKS = 0;TRUNCATE TABLE departments;INSERT departments SELECT * FROM departments_tmp;SET FOREIGN_KEY_CHECKS = 1;");

        // Add description to root department
        $rootDepartment = $em->getRepository("ShopIlluminationAdminBundle:Department")->findOneBy(array(
            'lvl' => 0,
        ));
        if($rootDepartment)
        {
            $description = new Department\Description();
            $description->setDepartment($rootDepartment);
            $description->setLocale('en');
            $description->setName("Root Department");
            $description->setMenuTitle("Root Department");
            $description->setPageTitle("Root Department");
            $description->setHeader("Root Department");
            $rootDepartment->addDescription($description);

            $em->persist($rootDepartment);
            $em->flush();
        }

        $output->writeln('Finished!');
    }

    private function persistChildren(DepartmentTmp $newParent, Department $parent, $children) {
        $em = $this->getContainer()->get('doctrine')->getManager();

        foreach($children as $child) {
            $newDepartment = $this->createTempDepartment($child, $newParent);
            $em->persist($newDepartment);

            if(count($child->getChildren()) > 0) {
                $this->persistChildren($newDepartment, $child, $child->getChildren());
            }
        }
    }

    private function createTempDepartment(Department $department, $parent) {
        $new = new DepartmentTmp();

        $new->setId($department->getId());
        $new->setParent($parent);
        $new->setStatus($department->getStatus());
        $new->setDepartmentPath($department->getDepartmentPath());
        $new->setHidePrices($department->getHidePrices());
        $new->setTemplate($department->getTemplate());
        $new->setShowPricesOutOfHours($department->getShowPricesOutOfHours());
        $new->setDeliveryBand($department->getDeliveryBand());
        $new->setInheritedDeliveryBand($department->getInheritedDeliveryBand());
        $new->setDisplayOrder($department->getDisplayOrder());
        $new->setCreatedAt($department->getCreatedAt());
        $new->setUpdatedAt($department->getUpdatedAt());

        return $new;
    }
}