<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Milestone;
use App\Task;
use App\Timetracking;
use App\Project;
use Carbon\Carbon;
use App\Mail\WeeklyOverview;

class ProjectsController extends Controller
{
	public function task()
	{
		return 'hi';
	}

	public function send()
	{
		$allemployees = Employee::all();
		$current_date = Carbon::now()->format('Y-m-d');
		$weekago = Carbon::today()->subWeek()->format('Y-m-d');
		$milestone_ids = array();
		$project_ids = array();

		foreach ($allemployees as $employee) {
		
			$employees = Employee::where('employee_id', $employee->employee_id)->get();

			$timetrackings = Timetracking::where('employee', $employee->employee_id)->whereDate('created_at','>',$weekago)->get();

			$tasks = Task::where('asignee', $employee->employee_id)->whereDate('created_at','>',$weekago)->get();

			foreach ($tasks as $task) {
				$milestone_ids[] = $task->milestone;
			}

			foreach ($timetrackings as $timetracking) {
				$milestone_ids[] = $timetracking->milestone;
			}
			$milestone_ids = array_unique($milestone_ids);
			$milestones = Milestone::whereIn('milestone_id',$milestone_ids)->get();

			foreach ($milestones as $milestone) {
				$project_ids[] = $milestone->project_id;
			}

			$project_ids = array_unique($project_ids);
			$projects = Project::whereIn('project_id',$project_ids)->get();
			foreach ($projects as $project) {
				$id = $project->project_id;
				$start = strtotime($project->starts_on);
				$finish = strtotime($project->due_on);
				$now = strtotime($current_date);
				//days between From and To
				$datediffA = round(($finish- $start) / (60 * 60 * 24));
				//days between From and Current
				$datediffB =  round(($now- $start) / (60 * 60 * 24));
				
				# code...

				$percentage = ($datediffB*100)/$datediffA;
				$project_time[$id] =floor($percentage);

			}
		;
			$data = compact('milestones', 'tasks', 'timetrackings', 'projects','employees','current_date','project_time');

			
			//dd($tasks);
			//\Mail::to('matic@wirelab.nl')->send(new WeeklyOverview($data));
			return view('projects/test', $data);

		}
	}

	public function update()
	{
	    	$weekago = Carbon::today()->subWeek()->format('Y-m-d');
	    	$current_date = Carbon::now()->format('Y-m-d');
	    	$timesran = 0;
	    	$categories = array('projects.list','users.list','milestones.list','tasks.list','timeTracking.list');
	    	$n = 1;
			$s = 1; 

	    	//*******************AUTHENTICATION**********************// 
	        $current_date = Carbon::now()->format('Y-m-d');
	        $clientId = '2ed8911e6006385991f7e286724dfaf9';
	        $clientSecret = '1cfaace0e0f7c2e771ed466e6d97bbdb';
		    /**
		     * Where to redirect to after the OAuth 2 flow was completed.
		     * Make sure this matches the information of your integration settings on the marketplace build page.
		     */
		    
		    $redirectUri = 'http://127.0.0.1:8000/update';
		    /* ------------------------------------------------------------------------------------------------- */
		    /**
		     * When the OAuth2 authentication flow was completed, the user is redirected back with a code.
		     * If we received the code, we can get an access token and make API calls. Otherwise we redirect
		     * the user to the OAuth2 authorization endpoints.
		     */
	    
		    if (!empty($_GET['code'])) {
		        $code = rawurldecode($_GET['code']);
		        
		    	/**
		         * Request an access token based on the received authorization code.
		         */
		        $ch = curl_init();
		        curl_setopt($ch, CURLOPT_URL, 'https://app.teamleader.eu/oauth2/access_token');
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		        curl_setopt($ch, CURLOPT_POST, true);
		        curl_setopt($ch, CURLOPT_POSTFIELDS, [
		            'code' => $code,
		            'client_id' => $clientId,
		            'client_secret' => $clientSecret,
		            'redirect_uri' => $redirectUri,
		            'grant_type' => 'authorization_code'
		        ]);
		        $response = curl_exec($ch);
		        $data = json_decode($response, true);
		        $accessToken = $data['access_token'];
		        
		        while ($timesran < sizeof($categories)) {
		        $n = 1;
				$s = 1; 
		        $projects = array();

		        while ($n == $s) {
		        	//*******************FILTERS**********************//
			    	$filters = array(
			    		array(//projects.list filter
			            'page' => array(
			                'size' =>  '99',
			                'number' => $s
			            		),
			            'sort' => [
			                array(
			                'field' => 'due_on',
			                'order' => 'desc'
			            		)
			            	]
			        	),
			        	array( //users.list filter
			            'filter' => array(
			                'status' => array('active')
			            		),
			            'page' => array(
			                'size' => '99',
			                'number' => '1'
			            		),
			            'sort' => [
			                array(
			                'field' => 'first_name',
			                'order' => 'asc'
			            		)
			            	]
			        	),
			        	array( // milestones.list filter
		                'filter' => array(
		                   
		                    'due_after'=>$weekago
		                ),
			            'page' => array(
			                'size' => '99',
			                'number' => $s
			            		),
			            'sort' => [
			                array(
			                'field' => 'due_on',
			                'order' => 'asc'
				            	)
				            ]
			        	),
			        	array( // tasks.list filter
		                'filter' => array(
		                    'due_from'=>$weekago
		                			),
				        'page' => array(
				        	'size' => '99',
				        	'number' => $s
				            		),
				        'sort' => [
				        	array(
				                'field' => 'due_on',
				                'order' => 'asc'
				            	)
				        ]
				        ),
				        array(// timeTracking.list filter
		                'page' => array(
		                    'size' => '99',
		                    'number' => 1
		                	),
		                'sort' => [
		                    array(
		                    'field' => 'due_on',
		                    'order' => 'asc'
		                	)
		                ]
		            	)
			    	);
	    			//*******************API CALL**********************//
			        $ch = curl_init();
			        curl_setopt($ch, CURLOPT_URL, 'https://api.teamleader.eu/'.$categories[$timesran]);
			        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
			       	curl_setopt($ch, CURLOPT_POSTFIELDS,    http_build_query($filters[$timesran]));
			        
			        //curl_setopt($ch, CURLOPT_POSTFIELDS,   'id=7478f72e-5c33-0cdf-896c-6c638ea7d3f0' );
			        $response = curl_exec($ch);

			        //decode response and save project ids into array
			        $datacontent = json_decode($response, true);
			        $datacontent = $datacontent['data'];
			        //*******************WRITE TO DB**********************//
			        switch ($timesran) {
			        	case 1:
					        foreach ($datacontent as $key => $entry) {
				      			
				      			if (isset($entry['last_name'])) {
								$task = Employee::firstOrCreate(['employee_id'=> $entry['id']],
									[
						            'account_id' => $entry['account']['id'],
						            'first_name' => $entry['first_name'],
						            'last_name' => $entry['last_name'],
						            'email' => $entry['email'],
						            'function' => $entry['function']
						            ]);
					        	}
					        }
					        $n++;
			        		break;
			        	case 2:
					        if (isset($datacontent[0]['id'])) {
					        	$s++;
					        }
					        foreach ($datacontent as $key => $entry) {
				      			
				      			if (isset($entry['responsible_user'])) {
				      	
								$milestone = Milestone::firstOrCreate(['milestone_id'=> $entry['id']],
									[
						            'due_on' => $entry['due_on'],
						            'name' =>$entry['name'],
						            'project_id' => $entry['project']['id'],
						            'responsible_user' => $entry['responsible_user']['id']
						            ]);
					        	}
					        }
					      	
							$n++;
			        		break;
			        	case 3:
			       
					        if (isset($datacontent[0]['id'])) {
					        	$s++;
					        }
					        foreach ($datacontent as $key => $entry) {

				      			if (isset($entry['milestone']['id'])) {
				      			
								$task = Task::firstOrCreate(['task_id'=> $entry['id']],
									[
						            'task_id' => $entry['id'],
						            'description' => $entry['description'],
						            'completed' => $entry['completed'],
						            'completed_at' => $entry['completed_at'],
						            'est_duration' => $entry['estimated_duration']['value'],
						            'milestone' => $entry['milestone']['id'],
						            'asignee' => $entry['assignee']['id'],
						            'due_at' => $entry['due_at']
						            ]);
					        	}
					        }
					        
					        $n++;
			        		break;
			        	case 4:
					        foreach ($datacontent as $key => $entry) {
				      			
				      			if (isset($entry['subject']['id'])) {
								$task = Timetracking::firstOrCreate(['timetracking_id'=> $entry['id']],
									[
						            'employee' => $entry['user']['id'],
						            'started_on' => $entry['started_on'],
						            'started_at' => $entry['started_at'],
						            'ended_at' => $entry['ended_at'],
						            'duration' => $entry['duration'],
						            'description' => $entry['description'],
						            'milestone' => $entry['subject']['id']
						            ]);
					        	}
					        }
					        $n++;
			        		break;		        	
			        	default:
					        if (isset($datacontent[0]['id'])) {
					        	$s++;
					        }
					        if(isset($datacontent[0]['id'])) {
					            foreach ($datacontent as $key => $entry) {
								$project = Project::firstOrCreate(['project_id'=> $entry['id']],
									[
						            'project_id' => $entry['id'],
						            'company_id' => $entry['customer']['id'],
						            'reference' => $entry['reference'],
						            'title' => $entry['title'],
						            'description' => $entry['description'],
						            'starts_on' => $entry['starts_on'],
						            'due_on' => $entry['due_on']
						            ]);
								}
								
					        }
					    	$n++;
			        		break;
			        }
		    	}
		    $timesran++;
		    }
			
	    } else {
	        //runs if 'code' is not set
	       $query = [
	            'client_id' => $clientId,
	            'response_type' => 'code',
	            'redirect_uri' => $redirectUri,
	        ];
	        header('Location: https://app.teamleader.eu/oauth2/authorize?' . http_build_query($query));
	        die;
	    }
	    
	    return view('auth.index', compact('data'));
	}
}
