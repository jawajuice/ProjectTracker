<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
	protected $guarded = [];
    public function employee(){

    	return $this->belongsTo(Employee::class);
    }
    public function milestone(){ 

    	return $this->belongsTo('App\Milestone', 'milestone_id');
    }
}
