<?php

namespace App\Models;

/**
 * Consignment model
 *
 * Class Consignment
 *
 * @package App\Models
 */
class Consignment
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $courierName;

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setCourierName(string $name)
    {
        $this->courierName = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getCourierName()
    {
        return $this->courierName;
    }
}
