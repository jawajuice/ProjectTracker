<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
	protected $guarded = [];
    public function project(){ //$project->employee

    	return $this->belongsTo('App\Project', 'project_id');
    }
    public function timetracking(){ //$project->employee

    	return $this->belongsTo('App\Timetracking', 'timetracking_id');
    }
}
