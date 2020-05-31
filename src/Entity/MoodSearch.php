<?php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class MoodSearch {

    private $date;

    
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */ 
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }
}