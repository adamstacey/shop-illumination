<?php
namespace ShopIllumination\UserBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="ShopIllumination\AdminBundle\Entity\Contact", mappedBy="user", cascade={"persist", "remove"})
     */
    private $contacts;

    public function __construct()
    {
        parent::__construct();

        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Sets the email.
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->setUsername($email);

        return parent::setEmail($email);
    }

    /**
     * Set the canonical email.
     *
     * @param string $emailCanonical
     * @return User
     */
    public function setEmailCanonical($emailCanonical)
    {
        $this->setUsernameCanonical($emailCanonical);

        return parent::setEmailCanonical($emailCanonical);
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add contact
     *
     * @param \ShopIllumination\AdminBundle\Entity\Contact $contact
     * @return User
     */
    public function addContact(\ShopIllumination\AdminBundle\Entity\Contact $contact)
    {
        $this->contacts[] = $contact;
        $contact->setUser($this);

        return $this;
    }

    /**
     * Remove contact
     *
     * @param \ShopIllumination\AdminBundle\Entity\Contact $contact
     */
    public function removeContact(\ShopIllumination\AdminBundle\Entity\Contact $contact)
    {
        $this->contacts->removeElement($contact);
    }

    /**
     * Get contacts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Set contacts
     *
     * @param \ShopIllumination\AdminBundle\Entity\Contact $contacts
     * @return User
     */
    public function setContacts(\ShopIllumination\AdminBundle\Entity\Contact $contacts = null)
    {
        $this->contacts = $contacts;
    
        return $this;
    }
}