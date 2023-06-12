<?php

namespace App\Http\Controllers\Absensi\Gaji;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Auth;
use App\Models\erp\payrollinternship;
use App\Models\erp\payrollinternshipitem;

// Import public function controller for logger
use App\Http\Controllers\Public_Function_Controller;

class GajiMagangController extends Controller
{
    public function __construct(Public_Function_Controller $Public_Function_Controller)
    {
        $this->Public_Function = $Public_Function_Controller;
    }

    // START REUSEABLE FUNCTION
    private function gettingSallary($idEmployee, $startDate, $endDate)
    {
        $data = FacadesDB::connection("erp")
        ->select("
            SELECT 
                A.TransDate, 
                B.Description, 
                B.ID, 
                '15000' nominal 
            From 
                workhour A 
                JOIN employee B ON B.ID = A.Employee 
            WHERE 
                A.Employee = " .$idEmployee. "
                AND TransDate Between '".$startDate."' AND '" .$endDate. "' 
                AND (A.Absent IS NULL OR (WorkIn Is NOT NULL OR WorkOut IS NOT NULL )) 
                AND (A.AbsentDay IS NULL OR A.AbsentDay = 'N') 
        ");

        $totalSallary = 0;
        foreach ($data as $key => $value) {
            $totalSallary+=$value->nominal;
        }
        $totalKerja = count($data);

        $data_return = [
            "sallary"=>$data,
            "totalKerja"=>$totalKerja,
            "totalSallary"=>$totalSallary
        ];
        return $data_return;
    }

    private function GetIDModule()
    {
        $IDModule = "218";
        return $IDModule;
    }

    private function ListUserHistory($UserID)
    {
        $tablename = "payrollinternship";
        $Module = $this->GetIDModule();
        // dd($UserID);
        $ListUserHistory = FacadesDB::connection('erp')->select(
            " SELECT
            U.UserID UserID,
            U.Module Module,
            U.HistList HistList,
            U.Description Description,
            U.EntryDate EntryDate,
            W.ID SW
        FROM
            userhistoryweb U
            JOIN " .
                $tablename .
                " W ON U.HistList = W.ID
        WHERE
            U.UserID = '$UserID'
            AND U.Module = '$Module'
        ORDER BY
            U.EntryDate DESC ",
        );
        // dd($ListUserHistory);
        return ($ListUserHistory);
    }

    private function GetEmployeeBySW($SW)
    {
        $employee = FacadesDB::connection('erp')
        ->table('Employee AS E')
        ->join('Department AS D', function($join){
            $join->on("E.Department","=","D.ID");
        })
        ->selectRaw("
            E.ID,
            E.SW,
            E.Description Name
        ")
        ->where("E.SW","=","".$SW)
        ->where("E.Active",'=',"Y")
        ->get();
        return $employee;
    }
    // END REUSEABLE FUNCTION 


    // Function for getting magang Employee
    public function GetEmployeeAndSallary(Request $request)
    {
        // Get Employee based on ID
        $employeeSW = $request->EmployeeSW;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        if (is_null($employeeSW) or $employeeSW == NULL) {
            $data_return = [
                'success' => false,
                'title' => 'Failed!!',
                'message' => "employeeSW parameters can't be Null or blank",
                'data' => null
            ];
            return response()->json($data_return,400);
        }
        
        // Get Employee
        $employee = FacadesDB::connection('erp')
        ->table('Employee AS E')
        ->join('Department AS D', function($join){
            $join->on("E.Department","=","D.ID");
        })
        ->selectRaw("
            E.ID,
            E.Description NAME,
            D.Description Bagian,
            E.Department,
            E.WorkRole	
        ")
        ->where("E.SW","=","".$employeeSW)
        ->orWhere("E.ID","=","".$employeeSW)
        ->where("E.Active",'=',"Y")
        ->orderBy("E.Department","ASC")
        ->get();

        // Check if Employee found
        if (count($employee) == 0) {
            $data_return = [
                'success' => false,
                'title' => 'Failed!!',
                'message' => "Employee with SW ".$employeeSW." Not Found",
                'data' => null
            ];
            return response()->json($data_return,404);
        } else {
            // Check if employee is "Magang Sekolah"
            if ($employee[0]->WorkRole !== "Magang Sekolah") {
                $data_return = [
                    'success' => false,
                    'title' => 'Failed!!',
                    'message' => "Employee with SW ".$employeeSW." is Not Magang Sekolah",
                    'data' => null
                ];
                return response()->json($data_return,400);
            }

            // Get TotalSallary and TotalWork
            $idEmployee = $employee[0]->ID;
            $sallaryAndWork = $this->gettingSallary($idEmployee,$startDate,$endDate);
            
            // Setup Return Data
            $data_return = [
                'success' => true,
                'title' => 'Successs!!',
                'message' => "Employee Found",
                'data'=> [
                    "employee"=>$employee[0],
                    "totalSallary"=>$sallaryAndWork['totalSallary'],
                    "totalKerja"=>$sallaryAndWork["totalKerja"]
                ]
            ];
            return response()->json($data_return,200);
        }
    }

    // Function for getting Employee magang sallary for info
    public function GetSallary(Request $request)
    {
        $idEmployee = $request->idEmployee;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        
        // Check if employee WorkRole is Magang
        $employee = FacadesDB::connection("erp")
        ->select("
            SELECT 
                WorkRole
            From
                Employee 
            WHERE 
                ID = " .$idEmployee
        );

        
        $data = FacadesDB::connection("erp")
        ->select("
            SELECT 
                A.TransDate, 
                B.Description, 
                B.ID, 
                '15000' nominal 
            From 
                workhour A 
                JOIN employee B ON B.ID = A.Employee 
            WHERE 
                A.Employee = " .$idEmployee. "
                AND TransDate Between '".$startDate."' AND '" .$endDate. "' 
                AND (A.Absent IS NULL OR (WorkIn Is NOT NULL OR WorkOut IS NOT NULL )) 
                AND (A.AbsentDay IS NULL OR A.AbsentDay = 'N') 
        ");

        $totalSallary = 0;
        foreach ($data as $key => $value) {
            $totalSallary+=$value->nominal;
        }

        $totalKerja = count($data);

        $data_return = [
            'success' => true,
            'title' => 'Successs!!',
            'message' => "Get Sallary Success",
            'data'=> [
                "sallary"=>$data,
                "totalSallary"=>$totalSallary,
                "totalKerja"=>$totalKerja
            ]
        ];
        return view("Absensi.Gaji.Magang.info", compact('data','totalSallary'));
    }

    // Function for saving sallary magang Employee
    public function SavePayroll(Request $request)
    {
        $user = Auth::user();
        // Data for payrollinternship
        $UserName = $user->name;
        $datenow = date('Y-m-d');
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        // Data for payrollinternshipitem
        $idEmployees = $request->idEmployees;
        
        // Insert Payroll
        $insertPayroll = payrollinternship::create([
            "UserName"=>$UserName,
            "EntryDate"=> date('Y-m-d H:i:s'),
            "TransDate"=>$datenow,
            "StartDate"=>$startDate,
            "EndDate"=>$endDate
        ]);

        // Getting payrollintership ID
        $payroll = payrollinternship::where("UserName","".$UserName)
        ->where("StartDate","".$startDate)
        ->where("EndDate","".$endDate)
        ->orderBy('ID','desc')
        ->first();
        
        $payrollID = $payroll->ID;

        $payrollitems = [];

        // Loop idEmployee
        foreach ($idEmployees as $employeekey => $idEmployee) {
            // Getting Payrollinternshipitem from WorkHour
            $sallaryAndWork = $this->gettingSallary($idEmployee,$startDate,$endDate);
            $totalKerja = $sallaryAndWork['totalKerja'];
            foreach ($sallaryAndWork['sallary'] as $key2 => $value) {
                $data = [
                    "TransDate"=>$value->TransDate,
                    "Description"=>$value->Description,
                    "ID"=>$value->ID,
                    "nominal"=>$value->nominal,
                    "totalKerja"=>$totalKerja
                ];
                
                array_push($payrollitems,$data);
            }
        }

        // Loop Payrollitem and insert it
        foreach ($payrollitems as $key => $value) {
            // Create Payrollinternshipitem
            $payrollitem = payrollinternshipitem::create([
                "IDM"=>$payrollID,
                "Ordinal"=>$key+1,
                "Employee"=>$value['ID'],
                "TotKerja"=>$value['totalKerja'],
                "Nominal"=>$value['nominal']
            ]);
        };

        // Insert this activity to useractivityweb
        // ->Getting ID Employee first
        $employee = FacadesDB::connection('erp')
        ->table('Employee AS E')
        ->join('Department AS D', function($join){
            $join->on("E.Department","=","D.ID");
        })
        ->selectRaw("
            E.ID,
            E.Description Name
        ")
        ->where("E.SW","=","".$user->name)
        ->where("E.Active",'=',"Y")
        ->get();

        // ->set UserID == employee[0]->ID
        $UserID = $employee[0]->ID;

        // ->Get IDModule
        $Module = $this->GetIDModule();

        // Insert to UserHistory web
        $UpdateUserHistory  = $this->Public_Function->UpdateUserHistoryERP($UserID, $Module, $payrollID);


        $data_return = [
            'success' => true,
            'title' => 'Success!!',
            'message' => "Payroll Saved",
            'data'=> [
                "status"=>"success",
                "payrollInternLastID"=> $payrollID
            ]
        ];
        return response()->json($data_return,200); 
    }

    public function SearchPayroll(Request $request)
    {
        $payrollID = $request->payrollID;

        // Check if payrollID is not null
        if (is_null($payrollID) or $payrollID == NULL) {
            $data_return = [
                'success' => false,
                'title' => 'Failed!!',
                'message' => "payrollID parameters can't be Null or blank",
                'data' => null
            ];
            return response()->json($data_return,400);
        }

        // Get payroll
        $data = FacadesDB::connection("erp")
        ->select("
            SELECT 
                A.ID,
                A.StartDate,
                A.EndDate,
                B.Employee,
                COUNT(B.TotKerja) as TotalKerja,
                SUM(B.Nominal) as TotalSallary,
                E.Description
            FROM
                payrollinternship A
                JOIN payrollinternshipitem B on A.ID = B.IDM
                JOIN employee E on B.Employee = E.ID
            WHERE
                A.ID = ".$payrollID."
            GROUP BY
                B.Employee
        ");

        // Check if payroll exists
        if (count($data) === 0){
            $data_return = [
                'success' => false,
                'title' => 'Failed!!',
                'message' => "payrollID not Found",
                'data' => null
            ];
            return response()->json($data_return,400);
        }

        $startDate = $data[0]->StartDate;
        $endDate = $data[0]->EndDate;

        // Return success
        $data_return = [
            'success' => true,
            'title' => 'Successs!!',
            'message' => "Get Sallary Success",
            'data'=> [
                "data"=>$data,
                "startDate"=>$startDate,
                "endDate"=>$endDate,
                "payrollID"=>$payrollID
            ]
        ];
        return response()->json($data_return);
    }

    // Cetak payroll
    public function CetakPayroll(Request $request)
    {
        $payrollID = $request->payrollid;
        if (is_null($payrollID) or $payrollID == NULL) {
            abort(404);
        }
        // Waktu Cetak
        $datenow = date('Y-m-d');

        $datapayroll = [];
        // Getting Payroll
        $data = FacadesDB::connection("erp")
        ->select("
            SELECT
                A.StartDate,
                A.EndDate,
                CONCAT(DATE_FORMAT(A.StartDate, '%d/%m/%Y'), ' ', 's/d', ' ', DATE_FORMAT(A.EndDate, '%d/%m/%Y')) AS periode,
                SUM(B.Nominal) as TotalSallary,
                15000 as Sallary,
                B.Employee as IDEmployee,
                COUNT(B.TotKerja) as Kehadiran,
                E.Description as NamaKaryawan,
                D.Description as Department
            FROM
                payrollinternship A
                JOIN payrollinternshipitem B on A.iD = B.IDM
                JOIN employee E on B.Employee = E.ID
                JOIN department D ON D.ID = E.Department
            WHERE
                A.ID=".$payrollID."
            GROUP BY B.Employee
        ");
        if (count($data) == 0) {
            abort(404);
        }
        foreach ($data as $key => $value) {
            // Getting Absent Details
            $startDate = $value->StartDate;
            $endDate = $value->EndDate;
            $idEmployee = $value->IDEmployee;
            $absentDetail = FacadesDB::connection("erp")
            ->select("
            SELECT 
                C.Description as AbsentType,
                CONCAT(COUNT(A.ID),' Hari') JumlahAbsent
            FROM
                absent A
                JOIN absentitem B on A.ID=B.IDM
                JOIN absenttype C on A.Type=C.ID
            WHERE 
                A.Employee = ".$idEmployee."
                AND
                B.TransDate BETWEEN '".$startDate."' AND '".$endDate."'
            GROUP BY A.Type
            ");

            // setup Return
            $dicts = [
                "StartDate"=> $value->StartDate,
                "EndDate"=>$value->EndDate,
                "periode"=>$value->periode,
                "TotalSallary"=>$value->TotalSallary,
                "Sallary"=>$value->Sallary,
                "IDEmployee"=>$value->IDEmployee,
                "Kehadiran"=>$value->Kehadiran,
                "NamaKaryawan"=>$value->NamaKaryawan,
                "Department"=>$value->Department,
                "absentDetail"=>$absentDetail
            ];
            array_push($datapayroll, $dicts);
        }
        return view("Absensi.Gaji.Magang.cetak", compact('datapayroll','datenow'));
    }

    // Function ubah payroll
    public function UbahPayroll(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $idEmployees = $request->idEmployees;
        $payrollID = $request->payrollID;

        // Delete payrollitem
        $deletePayrollitem = payrollinternshipitem::where('IDM',$payrollID)->delete();

        // Update Payroll
        $updatePayroll = payrollinternship::where('ID',$payrollID)->update([
            "StartDate"=>$startDate,
            "EndDate"=>$endDate
        ]);

        $payrollitems = [];

        // Loop idEmployees
        foreach ($idEmployees as $employeekey => $idEmployee) {
            // Getting Payrollinternshipitem from WorkHour
            $sallaryAndWork = $this->gettingSallary($idEmployee,$startDate,$endDate);
            $totalKerja = $sallaryAndWork['totalKerja'];
            foreach ($sallaryAndWork['sallary'] as $key2 => $value) {
                $data = [
                    "TransDate"=>$value->TransDate,
                    "Description"=>$value->Description,
                    "ID"=>$value->ID,
                    "nominal"=>$value->nominal,
                    "totalKerja"=>$totalKerja
                ];
                
                array_push($payrollitems,$data);
            }
        }

        // Loop Payrollitem and insert it
        foreach ($payrollitems as $key => $value) {
            // Create Payrollinternshipitem
            $payrollitem = payrollinternshipitem::create([
                "IDM"=>$payrollID,
                "Ordinal"=>$key+1,
                "Employee"=>$value['ID'],
                "TotKerja"=>$value['totalKerja'],
                "Nominal"=>$value['nominal']
            ]);
        };

        $data_return = [
            'success' => true,
            'title' => 'Success!!',
            'message' => "Payroll Updated",
            'data'=> [
                "status"=>"success",
                "payrollInternLastID"=> $payrollID
            ]
        ];
        return response()->json($data_return,200);
    }

    // Index function
    public function index()
    {
        $datenow = date('Y-m-d');

        // Getting History
        $user = Auth::user();
        $sw = $user->name;
        $UserID = $this->GetEmployeeBySW($sw);
        if (count($UserID) !== 0){
            $history  = $this->ListUserHistory($UserID[0]->ID);
        }else{
            $history = [];
        }

        return view("Absensi.Gaji.Magang.index2",compact('datenow','history'));
    }

    public function TrySomething()
    {
        // Getting History
        $user = Auth::user();
        $sw = $user->name;
        $UserID = $this->GetEmployeeBySW($sw);
        if (count($UserID) !== 0){
            $history  = $this->ListUserHistory($UserID[0]->ID);
        }else{
            $history = [];
        }
        dd($history);
    }
}
