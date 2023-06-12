<?php

namespace App\Http\Controllers\Absensi\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class InformasiFaceAbsentController extends Controller{
    private function BaseURL(){
        $_baseURL = "http://192.168.1.153:8081";
        return $_baseURL;
    }
    public function Index(){
        // Guzzle Http Client Initiation
        $client = new Client();
        // URL For getting JWT Token
        $bioTimeLoginUrl = $this->BaseURL()."/jwt-api-token-auth/";
        // URL For Getting Employee in BioTime
        $bioTimeEmployeeUrl = $this->BaseURL()."/personnel/api/employees/";
        $user = [
            "username"=>"programmer",
            "password"=>"Pro12345"
        ];

        // Getting JWT Token
        $response = $client->request('POST', $bioTimeLoginUrl, [
            "json"=>$user
        ]);
        $responseBody = json_decode($response->getBody());
        $jwtToken = $responseBody->token;

        $registeredEmployeeList = [];
        $unregisteredEmployeeList = [];
        $haveNext = true;
        while ($haveNext) {
            // Getting Employee
            $response = $client->request('GET', $bioTimeEmployeeUrl, [
                "headers"=>['Authorization'=> "JWT ".$jwtToken]
            ]);
            $responseBody = json_decode($response->getBody());
            foreach ($responseBody->data as $key => $value) {
                $temp = [
                    "employee_name"=> $value->full_name,
                    "id_emp_biotime"=>$value->id,
                    "emp_code"=>$value->emp_code,
                    "vl_face"=> $value->vl_face == 1 ? 1 : 0
                ];
                if ($value->vl_face == 1) {
                    $registeredEmployeeList[] = $temp;
                } else {
                    $unregisteredEmployeeList[] = $temp;
                }
                
            }
            if (is_null($responseBody->next)) {
                $haveNext = false;
            }
            $bioTimeEmployeeUrl = $responseBody->next;
        }

        return view('Absensi.Informasi.InformasiFaceAbsent.index', compact('registeredEmployeeList','unregisteredEmployeeList'));
    }
}
