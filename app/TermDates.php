<?php

namespace App;

use Carbon\Carbon;

class TermDates {

    protected $date = null;
    protected $_tDates = [];

    public function __construct($y = 0)
    {
        $y = ( int ) $y;
        if ($y < 2000) {
            $y = $this->getTermYear ();
        }
        $this->setTermDates ( $y );
    }

    public function setTermDates($y1) {
        $y2 = $y1 + 1;
        $this->_tDates = array (
            "$y1-09-01",
            "$y1-11-01",
            "$y2-01-01",
            "$y2-02-15",
            "$y2-04-15",
            "$y2-06-01",
            "$y2-08-31"
        );
    }

    public function getTermYear()
    {
        $cMonth = Carbon::now()->format("n");
        if($cMonth >= 1 && $cMonth < 8) {
            $cYear = Carbon::now()->addYear(-1)->format("Y");
        } else {
            $cYear = Carbon::now()->format("Y");
        }
        return $cYear;

    }

    public function getFirstDate()
    {
        return $this->_tDates[0];
    }

    public function getEndDate()
    {
        return end($this->_tDates);
    }


}