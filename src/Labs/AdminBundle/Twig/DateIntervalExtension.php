<?php
namespace Labs\AdminBundle\Twig;

class DateIntervalExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('dateInterval', array($this, 'dateIntervalFilter')),
        );
    }

    /**
     * @param $date
     * @return string
     */
    public function dateIntervalFilter($date)
    {
        $interval = new \DateInterval($date);
        return $interval->format('%i minute %s');
    }

    public function getName()
    {
        return 'date_interval_extension';
    }
}
