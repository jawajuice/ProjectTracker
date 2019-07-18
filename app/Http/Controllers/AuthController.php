<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function timeTracking() 
    {
    {
            $current_date = Carbon::now()->format('Y-m-d');
            
            $clientId = '2ed8911e6006385991f7e286724dfaf9';
            $clientSecret = '1cfaace0e0f7c2e771ed466e6d97bbdb';
        /**
         * Where to redirect to after the OAuth 2 flow was completed.
         * Make sure this matches the information of your integration settings on the marketplace build page.
         */
        $redirectUri = 'http://127.0.0.1:8000/auth';
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
            /**
             * Get the user identity information using the access token.
             */
            $n = 1;
            $s = 1;
            $projects = array();

            //while ($n == $s) {
            $filter = array(
                'filter' => array(
                    'status' => 'open',
                    'due_after'=>$current_date
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
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.teamleader.eu/timeTracking.info');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
            //curl_setopt($ch, CURLOPT_POSTFIELDS,    'id=b8a6e709-4320-0265-8450-6ecd7c343cc5');
            //curl_setopt($ch, CURLOPT_POSTFIELDS,    http_build_query($filter));
    /*        curl_setopt($ch, CURLOPT_POSTFIELDS, "{
              \"filter\": {
                \"status\": \"active\"
              }
            }");*/

            $response = curl_exec($ch);

            //decode reponse and save project ids into array
            $data = json_decode($response, true);
            $data = $data['data'];

            dd($data);
            if(isset($data[0]['id'])) {
                $s++;
                foreach ($data as $line) {
                $projects[] = $line['id'];
               
            }

            }
            $n++;

        //}

            foreach ($projects as $project) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.teamleader.eu/timeTracking.info');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
            curl_setopt($ch, CURLOPT_POSTFIELDS,    'id='.$project);
            $response = curl_exec($ch);

            //decode reponse and save project ids into array
            $data = json_decode($response, true);
            $data = $data['data'];


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

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
    }
    public function users() {
             //
        $clientId = '2ed8911e6006385991f7e286724dfaf9';
        $clientSecret = '1cfaace0e0f7c2e771ed466e6d97bbdb';
    /**
     * Where to redirect to after the OAuth 2 flow was completed.
     * Make sure this matches the information of your integration settings on the marketplace build page.
     */
    $redirectUri = 'http://127.0.0.1:8000/users';
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
        /**
         * Get the user identity information using the access token.
         */
/*        $databody = array('id'=>'b8dd0e55-40a5-0902-945c-8f9e94da326c');
        $databody = json_encode($databody);*/
        $n = 1;
        $s = 1;
        $projects = array();

      
        $filter = array(
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
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.teamleader.eu/users.list');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
        curl_setopt($ch, CURLOPT_POSTFIELDS,    http_build_query($filter));
/*        curl_setopt($ch, CURLOPT_POSTFIELDS, "{
          \"filter\": {
            \"status\": \"active\"
          }
        }");*/

        $response = curl_exec($ch);

        //decode reponse and save project ids into array
        $data = json_decode($response, true);

        $data = $data['data'];

        if(isset($data[0]['id'])) {
            $s++;
            foreach ($data as $line) {
            $projects[] = $line['id'];
           
        }

        }
        
        $n++;

        foreach ($projects as $project) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.teamleader.eu/projects.info');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
        curl_setopt($ch, CURLOPT_POSTFIELDS,    'id='.$project);
        $response = curl_exec($ch);

        //decode reponse and save project ids into array
        $data = json_decode($response, true);
        $data = $data['data'];

        
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
    
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
