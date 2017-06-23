<?php
/**
 * Event
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 */
class Event
{
    const NUM_ITEMS = 10;
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
     * profile
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="events")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    private $profile;

    /**
     * location
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="events")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id", nullable=true)
     */
    private $location;

    /**
     * startDate
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="date")
     * @Assert\NotNull()
     */
    private $startDate;

    /**
     * startTime
     * @var \DateTime
     *
     * @ORM\Column(name="startTime", type="time", nullable=true)
     */
    private $startTime;

    /**
     * endDate
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="date")
     * @Assert\NotNull()
     */
    private $endDate;

    /**
     * endTime
     * @var \DateTime
     *
     * @ORM\Column(name="endTime", type="time", nullable=true)
     */
    private $endTime;

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
     * description
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * color
     * @ORM\ManyToOne(targetEntity="Color", inversedBy="events")
     * @ORM\JoinColumn(name="color_id", referencedColumnName="id")
     */
    private $color;

    /**
     * notes
     * @ORM\OneToMany(targetEntity="Note", mappedBy="event")
     */
    private $notes;

    /**
     * Many Events have Many Participants.
     * @ORM\ManyToMany(targetEntity="Contact", mappedBy="events")
     */
    private $participants;

    /**
     * Event constructor.
     */
    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->participants = new ArrayCollection();
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
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Event
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return Event
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Event
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     * @return Event
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Event
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
     * @return Event
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
     * Set profile
     *
     * @param Profile $profile
     * @return Event
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
     * Set location
     *
     * @param Location $location
     * @return Event
     */
    public function setLocation(Location $location = null)
    {
        $this->location = $location;

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
     * Set color
     *
     * @param Color $color
     * @return Event
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
     * Add notes
     *
     * @param Note $notes
     * @return Event
     */
    public function addNote(Note $notes)
    {
        $this->notes[] = $notes;

        return $this;
    }

    /**
     * Remove notes
     *
     * @param Note $notes
     */
    public function removeNote(Note $notes)
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

    /**
     * addParticipant
     * @param Contact $participant
     */
    public function addParticipant(Contact $participant)
    {
        $participant->addEvent($this); // synchronously updating inverse side
        $this->participants->add($participant) ;
    }

    /**
     * Remove participants
     *
     * @param Contact $participant
     * @internal param Contact $participants
     */
    public function removeParticipant(Contact $participant)
    {
        $this->participants->removeElement($participant);
    }

    /**
     * Get participants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipants()
    {
        return $this->participants;
    }
}
