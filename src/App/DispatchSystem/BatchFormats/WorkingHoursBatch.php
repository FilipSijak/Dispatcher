<?php

namespace App\DispatchSystem\BatchFormats;

/**
 * Dispatch period starts with a normal working day and closes by the end of the working day
 *
 * I won't dive deep into this due to allowed time for this task, but my understanding of the task is that parcels
 *  will only be taken within a specific time interval on a regular working day.
 *
 * For the sake of flexibility, I've taken this approach in case there is a similar requirement for weekends,
 * or some emergency dispatches.
 * If that would be the case, I would rather use another implementation of DispatchPeriodInterface
 * instead of tweaking this class to fit the needs.
 *
 * Class WorkingHoursBatch
 *
 * @package App\DispatchSystem\BatchFormats
 */
class WorkingHoursBatch implements DispatchPeriodInterface
{
    /**
     *
     * @return bool
     */
    public function isDispatchAvailable()
    {
        // TODO: Implement isDispatchAvailable() method.

        return true;
    }
}