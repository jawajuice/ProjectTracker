<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use App\Employee;
use App\Milestone;
use App\Task;
use App\Timetracking;
use App\Project;
use Carbon\Carbon;

class WeeklyOverview extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
 
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('projects/test')
                    ->with([
                        'milestones' => $this->data['milestones'],
                        'tasks' => $this->data['tasks'],
                        'timetrackings' => $this->data['timetrackings'],
                        'projects' => $this->data['projects'],
                        'employees' => $this->data['employees'],
                        'current_date' => $this->data['current_date']

                    ]);
    }
}
