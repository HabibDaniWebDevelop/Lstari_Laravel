<?php

namespace App\Http\Controllers\API\WorkLog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class WorklogApiController extends Controller{
    // START REUSABLE FUNCTION
    private function SetReturn($success,$message,$data,$error){
        $data_return = [
            "success"=>$success,
            "message"=>$message,
            "data"=>$data,
            "error"=>$error
        ];
        return $data_return;
    }
    // End Reusable Function
    
    public function getEmployeeStatistics(Request $request){
        $idEmployee = $request->idEmployee;
        $operation = $request->operation;
        $today =  date('Y-m-d');

        $hasError = false;
        $errorMessages = [];

        // Check if idEmployee is not null or blank
        if (is_null($idEmployee) or $idEmployee == "") {
            $hasError = true;
            $errorMessages[] = ["params"=>"idEmployee","error"=>"idEmployee can't be blank or null"];
        }

        // Check if operation is not null or blank
        if (is_null($operation) or $operation == "") {
            $hasError = true;
            $errorMessages[] = ["params"=>"operation","error"=>"operation can't be blank or null"];
        }
        
        // Check if hasError
        if ($hasError) {
            $data_return = $this->SetReturn(false, "Transaction Error", null, $errorMessages);
            return response()->json($data_return, 400);
        }

        // Try to get SPKO Todo Today
        $spkoToday = FacadesDB::connection('erpnext')->select("
            SELECT
                (SELECT employee_name FROM `tabEmployee` WHERE id_employee = '$idEmployee') AS EmployeeName,
                A.employee_id,
                ROUND(SUM(A.percent)) AS today_schedule_percent
            FROM
                tabSPKO A
            WHERE
                A.creation LIKE '%$today%'
                AND A.employee_id = '$idEmployee'
        ");
        // Check if spkoToday exists
        if (count($spkoToday) == 0) {
            $spkoToday = [
                "employee_id"=>$idEmployee,
                "today_schedule_percent"=>0,
                "employee_name"=>null,
            ];
        }
        $spkoToday = $spkoToday[0];

        // Try to get Workclock Todo Today
        $workclockToday = FacadesDB::connection('erpnext')->select("
            SELECT
                (SELECT employee_name FROM `tabEmployee` WHERE id_employee = '$idEmployee') AS EmployeeName,
                A.employee_id,
                ROUND(
                    (
                        (
                            SUM( 
                                IF ( 
                                    DATE ( A.waktu_selesai ) = CURRENT_DATE (), A.total_detik, 0 ) 
                                ) /
                                CASE
                                    WHEN B.gender = 'Male' AND DAYNAME(CURRENT_DATE ()) = 'Friday' THEN 22500 
                                    WHEN DAYNAME(CURRENT_DATE ()) = 'Saturday' THEN 17640 
                                    ELSE 26100 
                                END 
                            ) * 100 
                        ),0 
                    ) today_workclock_percent 
            FROM
                `tabWork Log` A
                JOIN `tabEmployee` B ON B.NAME = A.employee 
            WHERE
                A.employee_id = '$idEmployee' 
                AND A.operation = '$operation'
        "); 
        // Check if workclockToday exists
        if (count($workclockToday) == 0) {
            $workclockToday = [
                "employee_id"=>$idEmployee,
                "today_workclock_percent"=>0,
                "employee_name"=>null,
            ];
        }
        $workclockToday = $workclockToday[0];
        $today_schedule_percent = $spkoToday->today_schedule_percent == null ? 0 : (int)$spkoToday->today_schedule_percent;
        // Set colors of today_schedule_percent
        $today_schedule_percent_color = "0XFFFF0032";
        if ($today_schedule_percent > 90) {
            $today_schedule_percent_color = "0xFF2146C7";
        } 
        if ($today_schedule_percent > 80 and $today_schedule_percent <= 90) {
            $today_schedule_percent_color = "0XFFADE792";
        } 
        if ($today_schedule_percent > 70 and $today_schedule_percent <= 80) {
            $today_schedule_percent_color = "0XFFFF00FF";
        } 
        if ($today_schedule_percent > 60 and $today_schedule_percent <= 70) {
            $today_schedule_percent_color = "0XFFFFB100";
        }

        $today_workclock_percent = $workclockToday->today_workclock_percent == null ? 0 : (int)$workclockToday->today_workclock_percent;
        // Set colors of today_workclock_percent
        $today_workclock_percent_color = "0XFFFF0032";
        if ($today_workclock_percent > 90) {
            $today_workclock_percent_color = "0xFF2146C7";
        } 
        if ($today_workclock_percent > 80 and $today_workclock_percent <= 90) {
            $today_workclock_percent_color = "0XFFADE792";
        } 
        if ($today_workclock_percent > 70 and $today_workclock_percent <= 80) {
            $today_workclock_percent_color = "0XFFFF00FF";
        } 
        if ($today_workclock_percent > 60 and $today_workclock_percent <= 70) {
            $today_workclock_percent_color = "0XFFFFB100";
        }

        $actual_data = [
            "idEmployee"=>$idEmployee,
            "today_schedule_percent"=>$today_schedule_percent,
            "today_schedule_percent_color"=>$today_schedule_percent_color,
            "today_workclock_percent"=>$today_workclock_percent,
            "today_workclock_percent_color"=>$today_workclock_percent_color,
            "operation"=>$operation,
        ];
        $data_return = $this->SetReturn(true, "Success", $actual_data, null);
        return response()->json($data_return, 200);
    }
}
