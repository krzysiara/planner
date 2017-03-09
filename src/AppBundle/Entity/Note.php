<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Note
 *
 * @ORM\Table(name="note")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NoteRepository")
 */
class Note
{
    const CONTACT_TYPE = 1;
    const EVENT_TYPE = 2;
    const LOCATION_TYPE = 3;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="Color", inversedBy="notes")
     * @ORM\JoinColumn(name="color_id", referencedColumnName="id")
     */
    private $color;

    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="notes")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", nullable=true)
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="notes")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id", nullable=true)
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity="Contact", inversedBy="notes")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id", nullable=true)
     */
    private $contact;

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
     * Set title
     *
     * @param string $title
     * @return Note
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Note
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Note
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set color
     *
     * @param \AppBundle\Entity\Color $color
     * @return Note
     */
    public function setColor(\AppBundle\Entity\Color $color = null)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return \AppBundle\Entity\Color 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set event
     *
     * @param \AppBundle\Entity\Event $event
     * @return Note
     */
    public function setEvent(\AppBundle\Entity\Event $event = null)
    {
        $this->event = $event;
        $this->setType($this::EVENT_TYPE);
        return $this;
    }

    /**
     * Get event
     *
     * @return \AppBundle\Entity\Event 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set location
     *
     * @param \AppBundle\Entity\Location $location
     * @return Note
     */
    public function setLocation(\AppBundle\Entity\Location $location = null)
    {
        $this->location = $location;
        $this->setType($this::LOCATION_TYPE);

        return $this;
    }

    /**
     * Get location
     *
     * @return \AppBundle\Entity\Location 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set contact
     *
     * @param \AppBundle\Entity\Contact $contact
     * @return Note
     */
    public function setContact(\AppBundle\Entity\Contact $contact = null)
    {
        $this->contact = $contact;
        $this->setType($this::CONTACT_TYPE);

        return $this;
    }

    /**
     * Get contact
     *
     * @return \AppBundle\Entity\Contact 
     */
    public function getContact()
    {
        return $this->contact;
    }
}
