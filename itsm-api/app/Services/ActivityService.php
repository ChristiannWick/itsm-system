<?php

namespace App\Services;

use App\Models\Activity;

class ActivityService
{

    public function log($ticketId,$userId,$type,$description)
    {

        Activity::create([

            'ticket_id'=>$ticketId,

            'user_id'=>$userId,

            'type'=>$type,

            'description'=>$description

        ]);

    }

}