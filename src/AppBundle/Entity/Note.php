<?php
/**
 * Note
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
    const NUM_ITEMS = 9;

    /**
     * id
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * title
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * desc
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     *
     */
    private $description;

    /**
     * type
     * @var int
     *
     * @ORM\Column(name="type", type="integer")
     * @Assert\NotNull()
     */
    private $type;

    /**
     * color
     * @ORM\ManyToOne(targetEntity="Color", inversedBy="notes")
     * @ORM\JoinColumn(name="color_id", referencedColumnName="id")
     */
    private $color;

    /**
     * event
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="notes")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", nullable=true)
     */
    private $event;

    /**
     * location
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="notes")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id", nullable=true)
     */
    private $location;

    /**
     * contact
     * @ORM\ManyToOne(targetEntity="Contact", inversedBy="notes")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id", nullable=true)
     */
    private $contact;

    /**
     * profile
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="notes")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    private $profile;


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
    public function setColor(Color $color = null)
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
    public function setEvent(Event $event = null)
    {
        $this->event = $event;
        $this->setType($this::EVENT_TYPE);
        $this->setProfile($event->getProfile());

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
    public function setLocation(Location $location = null)
    {
        $this->location = $location;
        $this->setType($this::LOCATION_TYPE);
        $this->setProfile($location->getProfile());

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
    public function setContact(Contact $contact = null)
    {
        $this->contact = $contact;
        $this->setType($this::CONTACT_TYPE);
        $this->setProfile($contact->getProfile());

        return $this;
    }


    /**
     * Set profile
     *
     * @param \AppBundle\Entity\Profile $profile
     * @return Location
     */
    public function setProfile(Profile $profile = null)
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
     * Get contact
     *
     * @return \AppBundle\Entity\Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * getColorName
     * @return string
     */
    public function getColorName()
    {
        return $this->color ? $this->getColor()->getName() : "-";
    }
}
