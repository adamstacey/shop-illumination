<?php
namespace ShopIllumination\AdminBundle\Manager;

use Doctrine\ORM\EntityManager;
use ShopIllumination\AdminBundle\Entity\DescribableInterface;
use ShopIllumination\AdminBundle\Entity\Document;
use ShopIllumination\AdminBundle\Entity\Product\Document as ProductDocument;
use ShopIllumination\AdminBundle\Entity\Product\Variant\Document as ProductVariantDocument;
use ShopIllumination\AdminBundle\Entity\Brand\Document as BrandDocument;
use ShopIllumination\AdminBundle\Entity\Department\Document as DepartmentDocument;
use ShopIllumination\AdminBundle\Entity\Product;
use ShopIllumination\AdminBundle\Entity\ProductToDepartment;
use ShopIllumination\AdminBundle\Entity\Routing;
use ShopIllumination\AdminBundle\Form\Product\ProductVariantDocumentsType;
use Symfony\Component\Filesystem\Filesystem;

class DocumentManager extends Manager
{
    private $fileManager;

    public function __construct($doctrine, FileManager $fileManager)
    {
        parent::__construct($doctrine);

        $this->fileManager = $fileManager;
    }

    public function createDocument($objectType)
    {
        switch ($objectType)
        {
            case 'brand':
                $document = new BrandDocument();
                break;
            case 'department':
                $document = new DepartmentDocument();
                break;
            case 'product_variant':
                $document = new ProductVariantDocument();
                break;
            case 'product':
                $document = new ProductDocument();
                break;
            default:
                return null;
        }
        $document->setDisplayOrder(0);
        $document->setDocumentType("document");
        return $document;
    }

    public function getClassName($objectType)
    {
        switch ($objectType)
        {
            case 'brand':
                return "ShopIllumination\\AdminBundle\\Entity\\Brand\\Document";
            case 'department':
                return "ShopIllumination\\AdminBundle\\Entity\\Department\\Document";
            case 'product_variant':
                return "ShopIllumination\\AdminBundle\\Entity\\Product\\Variant\\Document";
            case 'product':
                return "ShopIllumination\\AdminBundle\\Entity\\Product\\Document";
            default:
                return false;
        }
    }

    public function getObject($document)
    {
        switch (get_class($document))
        {
            case 'ShopIllumination\\AdminBundle\\Entity\\Brand\\Document':
                return $document->getBrand();
            case 'ShopIllumination\\AdminBundle\\Entity\\Department\\Document':
                return $document->getDepartment();
            case 'ShopIllumination\\AdminBundle\\Entity\\Product\\Variant\\Document':
                return $document->getVariant();
            case 'ShopIllumination\\AdminBundle\\Entity\\Product\\Document':
                return $document->getProduct();
            default:
                return null;
        }
    }

    public function process(Document $document, DescribableInterface $object)
    {
        $fs = new Filesystem();
        $fileSize = filesize($document->getUploadPath().$document->getPath());
        $fileExtension = $this->fileManager->getFileExtension($document->getUploadPath().$document->getPath());

        $filename = $this->fileManager->cleanFilename($object->getDescription()->getHeader());
        $basePath = $document->getUploadDir().'/'.$document->getObjectType().'/'.$document->getDocumentType();
        $filePath = $basePath.'/'.$filename.'-'.$document->getId().'.'.$fileExtension;
        $fs->rename($document->getUploadPath().$document->getPath(), $document->getUploadPath().$filePath, true);

        $document->setPath($filePath);
        $document->setFileExtension($fileExtension);
        $document->setFileSize($fileSize);
    }

    public function persistDocuments($object, $objectType)
    {
        $fs = new Filesystem();
        $em = $this->doctrine->getManager();

        // Go through the temporary documents
        if($object->getTemporaryDocuments() === "") return;

        $documentIds = explode(',', $object->getTemporaryDocuments());
        $displayOrder = 0;
        foreach ($documentIds as $documentId)
        {
            $document = $em->getRepository($this->getClassName($objectType))->find($documentId);
            if ($document)
            {
                if($this->getObject($document) == null) {
                    // Create a new document based on the object type
                    switch ($objectType)
                    {
                        case 'brand':
                            $newDocument = new BrandDocument();
                            $newDocument->setBrand($object);
                            break;
                        case 'department':
                            $newDocument = new DepartmentDocument();
                            $newDocument->setDepartment($object);
                            break;
                        case 'product_variant':
                            $newDocument = new ProductVariantDocument();
                            $newDocument->setVariant($object);
                            break;
                        case 'product':
                        default:
                            $newDocument = new ProductDocument();
                            $newDocument->setProduct($object);
                            break;
                    }

                    // Copy the data from the temporary document
                    $newDocument->setPath($document->getPath());
                    $newDocument->setLocale($document->getLocale());
                    $newDocument->setTitle($document->getTitle());
                    $newDocument->setDescription($document->getDescription());
                    $newDocument->setDocumentType($document->getDocumentType());
                    $newDocument->setFileExtension($document->getFileExtension());
                    $newDocument->setDisplayOrder($displayOrder);

                    $em->persist($newDocument);
                    $em->flush();

                    // Process the new document and add it to the object
                    $this->process($newDocument, $object);
                    $object->addDocument($newDocument);
                } else {
                    $document->setDisplayOrder($displayOrder);
                }

                $displayOrder++;
            }
        }
    }

    public function removeTemporaryDocuments($object)
    {
        /**
         * @var $em EntityManager
         * @var $document Document
         */
        $em = $this->doctrine->getManager();

        // Remove the temporary uploads
        $uploadIds = explode(',', $object->getDocumentUploads());
        foreach ($uploadIds as $uploadId)
        {
            $upload = $em->getRepository("ShopIllumination\AdminBundle\Entity\Document")->find($uploadId);
            if ($upload && $this->getObject($upload) == null)
            {
                $em->remove($upload);
            }
        }

        // Remove the temporary documents
        $documentIds = explode(',', $object->getTemporaryDocuments());
        foreach ($documentIds as $documentId)
        {
            $document = $em->getRepository("ShopIllumination\AdminBundle\Entity\Document")->find($documentId);
            if ($document && $this->getObject($document) == null)
            {
                $em->remove($document);
            }
        }

        // Flush the database
        $em->flush();
    }
}