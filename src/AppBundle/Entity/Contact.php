<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Contact
 *
 * @ORM\Table(name="contact")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContactRepository")
 */
class Contact
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="contacts")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    private $profile;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255, nullable=true)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="contacts")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id", nullable=true)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="Note", mappedBy="contact")
     */
    private $notes;

    /**
     * Many Contacts have Many Events.
     * @ORM\ManyToMany(targetEntity="Event", inversedBy="participants")
     * @ORM\JoinTable(name="events_participants")
     */
    private $events;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->events = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Contact
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return Contact
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Contact
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return Contact
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set profile
     *
     * @param \AppBundle\Entity\Profile $profile
     * @return Contact
     */
    public function setProfile(\AppBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \AppBundle\Entity\Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Add notes
     *
     * @param \AppBundle\Entity\Note $notes
     * @return Contact
     */
    public function addNote(\AppBundle\Entity\Note $notes)
    {
        $this->notes[] = $notes;

        return $this;
    }

    /**
     * Remove notes
     *
     * @param \AppBundle\Entity\Note $notes
     */
    public function removeNote(\AppBundle\Entity\Note $notes)
    {
        $this->notes->removeElement($notes);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    public function addEvent(Event $event)
    {
        $this->events->add($event) ;
    }


    /**
     * Remove events
     *
     * @param \AppBundle\Entity\Event $events
     */
    public function removeEvent(\AppBundle\Entity\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Set address
     *
     * @param \AppBundle\Entity\Location $address
     * @return Contact
     */
    public function setAddress(\AppBundle\Entity\Location $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \AppBundle\Entity\Location 
     */
    public function getAddress()
    {
        return $this->address;
    }
}
