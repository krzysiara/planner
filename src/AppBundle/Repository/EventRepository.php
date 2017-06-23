<?php
/**
 * EventRepository
 *
 */
namespace AppBundle\Repository;

use AppBundle\Entity\Event;
use AppBundle\Entity\Profile;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * EventRepository
 *
 */
class EventRepository extends EntityRepository
{
    /**
     * findTodayEvents
     * @param Profile   $profile
     * @param \DateTime $date
     * @return array
     */
    public function findTodayEvents($profile, $date)
    {
        $query = $this->createQueryBuilder('e');
        $query

            ->where('e.startDate = :date AND e.profile = :profile')
            ->orWhere('e.endDate = :date AND e.profile = :profile')
            ->orWhere('e.startDate <= :date AND e.endDate >= :date AND e.profile = :profile')

            ->setParameters([
                'profile' => $profile,
                'date' => $date,
            ])
            ->getQuery();

        return $query->getQuery()->getResult();
    }

    /**
     * findMonthEvents
     * @param Profile $profile
     * @param int     $month
     * @param int     $year
     * @return array
     */
    public function findMonthEvents($profile, $month, $year)
    {
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dayEvents = [];
        while ($days > 0) {
            $dateString = $year."/".$month."/".$days;
            $date = date_create_from_format("Y/m/d", $dateString);
            $dayEvents[$days] = $this->findTodayEvents($profile, $date);
            $days --;
        }

        return $dayEvents;
    }


    /**
     * findBetweenDates
     * @param Profile   $profile
     * @param \DateTime $from
     * @param \DateTime $to
     * @return array
     */
    public function findBetweenDates($profile, $from, $to)
    {
        $query = $this->createQueryBuilder('e');
        $query

            ->where('e.startDate <= :startDate AND e.endDate >= :startDate AND e.profile = :profile')
            ->orWhere('e.startDate <= :endDate AND e.endDate >= :endDate AND e.profile = :profile')
            ->orWhere('e.startDate >= :startDate AND e.endDate <= :endDate AND e.profile = :profile')
            ->setParameters(
                [
                    'profile' => $profile,
                    'startDate' => $from,
                    'endDate' => $to,
                ]
            )
            ->getQuery();

        return $query->getQuery()->getResult();
    }

    /**
     * Gets all records paginated.
     *
     * @param Profile $profile Profile
     * @param int     $page    Page number
     * @return \Pagerfanta\Pagerfanta Result
     */
    public function findAllPaginated($profile, $page = 1)
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($this->queryByUser($profile), false));
        $paginator->setMaxPerPage(Event::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * Query all entities.
     *
     * @param $profile Profile
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function queryByUser($profile)
    {
        return $this->createQueryBuilder('e')
            ->where("e.profile =:profile")
            ->orderBy("e.startDate")
            ->setParameter('profile', $profile);
    }
}
