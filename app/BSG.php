<?php

namespace App;

use Carbon\Carbon;

class BSG {

    protected $db;

    protected $query = [];

    public function __construct()
    {
        $this->db = \DB::connection('sqlsrv');
    }

    public function addToQueue() {

        if(count($this->query) > 0) {
            $this->db->table('queue')->insert($this->query);
        }

    }

    public function buildQuery($title, $person_id, $message, $priority=0) {

        $this->query[] = [
            'queuedate' => Carbon::now(),
            'title' => $title,
            'message' => $message,
            'priority' => $priority,
            'person_id' => $person_id
        ];

    }
}