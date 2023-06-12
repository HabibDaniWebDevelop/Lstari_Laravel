<?php

namespace App\Http\Controllers\Laboratorium;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use DateTime;

// Models
use App\Models\tes_laravel\labtransaction;
use App\Models\tes_laravel\labtransactionitem;
use App\Models\tes_laravel\labresultitem;

class LaboratoriumController extends Controller
{

    private function ProcessFile($file,$option){ // $option parameter is for decide result of file want to be stored or just showing it.
        // $filename = storage_path("temp/Test221128B.txt");
        // $file = File::get($filename);
        // $option = "view";
        // Split data by new line
        $splited = preg_split('/\n|\r\n?/', $file);
        // Array container for clean data
        $newdata = [];
    
        // Loop array of String
        for ($i=0; $i < count($splited); $i++) { 
            // Check if string is not empty string
            if ($splited[$i] != ""){
                // Removing '%' ')' '(' '[' ']' from string
                $trysome = str_replace(array( '(', ')', '%', '[', ']' ), '', $splited[$i]);
                // Removing whitespace
                $trysome =  preg_replace('/\s+/', ' ', $trysome);
                // Split by space
                $trysome = preg_split('/\s+/', $trysome);
                // push to container
                array_push($newdata, array_values(array_filter($trysome)));
            
            }
        }
        $newdata = array_values(array_filter($newdata));
        if ($newdata[0][1] == "6"){
            $result = $this->GoldGlobalType($newdata);
            $block = $newdata[0][8];
        } elseif ($newdata[0][1] == "26") {
            $result = $this->Product26Type($newdata);
            $block = $newdata[0][7];
        } else{
            $dataReturn = [
                "success"=>false,
                "message"=>"Failed to process. Invalid Document Format."
            ];
            return $dataReturn;
        }
        if ($option == "view") {
            // Set Lab Test Item
            // Set Container for Lab Test item
            $datalabtestitem = [];
            // Loop through result['testitem'] index 0 is the header
            foreach ($result['testitem'][0] as $key => $value) {
                // Set empty list for store the value
                $temp = [];
                for ($i=1; $i < count($result['testitem']); $i++) {
                    $temp[] = $result['testitem'][$i][$key];
                }
                // store value in container
                $datalabtestitem[$value] = $temp;   
            }
            // Pop 'n' from array
            array_shift($datalabtestitem);

            // Set Lab Result
            // Set Container for Lab Result item
            $datalabresultitem = [];
            for ($i=1; $i < 8 ; $i++) { 
                if ($i == 1 ){
                    $LabresultItem_type = "Mean";
                } elseif ($i == 2) {
                    $LabresultItem_type = "Standard deviation"; 
                } elseif ($i == 3) {
                    $LabresultItem_type = "C.O.V"; 
                } elseif ($i == 4) {
                    $LabresultItem_type = "Range"; 
                } elseif ($i == 5) {
                    $LabresultItem_type = "Number of readings"; 
                } elseif ($i == 6) {
                    $LabresultItem_type = "Min. reading"; 
                } elseif ($i == 7) {
                    $LabresultItem_type = "Max. reading"; 
                } 
                $temp = [];
                foreach ($result['testresult'][0] as $key => $value) {
                    $temp[$value] = $result['testresult'][$i][$key];
                }
                $datalabresultitem[$LabresultItem_type] = $temp;
            }
            
            $dataReturn = [
                "success"=>true,
                "message"=>"Extract Data Success",
                "testitem"=>$datalabtestitem,
                "testresult"=>$datalabresultitem,
                "operator"=>$result['operator'],
                "kadar"=>$result['kadar'],
                "SWSPK"=>$result['SWSPK'],
                "measuringTime"=>$result['measuringTime'],
                "TestDate"=>$result['TestDate'],
                "block"=>$block
            ];
            return $dataReturn;

        }elseif ($option = "save") {

            // THIS SCRIPT BELOW WILL INSERT TEST DATA TO DATABASE

            $TestQty = $result['TestQty'];
            $data1 = $result['testitem'];
            $data2 = $result['testresult'];
            $type = $result['type'];
            $operator = $result['operator'];
            $SWSPK = $result['SWSPK'];
            $carat = $result['kadar'];
            $measuringTime = $result['measuringTime'];
            $TestDate = $result['TestDate'];
            if ($type == 1){
                $typeDescription = "Product:  26 / AuAgCuZnNiPdFeCd";
            }elseif ($type == 2) {
                $typeDescription = "Product:  6 / Gold Global";
            } else {
                $typeDescription = "Else";
            }

            // Insert to labtransaction
            $insertLabTransaction = labtransaction::create([
                "UserName"=>"ArikBa",
                "Type"=>$type,
                "TypeDescription"=>$typeDescription,
                "TestQTY"=>$TestQty,
                "Operator"=>$operator,
                "SWSPK"=>$SWSPK,
                "Carat"=>$carat,
                "Block"=>$block,
                "MeasuringTime"=>$measuringTime,
                "TestDate"=>$TestDate
            ]);

            // Get id of transaction
            $id_trx = $insertLabTransaction->id;

            // Set head of data1
            $data1head = $data1[0];
            array_shift($data1head);
            // delete head from list
            array_shift($data1);
            
            $ordinalItem = 1;
            // Lab Transaction Item
            foreach ($data1 as $key => $value) {
                array_shift($value);
                for ($i=0; $i < count($data1head); $i++) { 
                    // Insert to DB
                    $insertLabItem = labtransactionitem::create([
                        "Labtransaction_id"=>$id_trx,
                        "Ordinal"=>$ordinalItem,
                        "Var"=>$data1head[$i],
                        "Val"=>$value[$i]
                    ]);
                    $ordinalItem +=1;
                }
            }

            // Lab Result Item
            // set Data2 head
            $data2head = $data2[0];
            // Remove head from list
            array_shift($data2);
            foreach ($data2 as $key => $value) {
                if ($key == 0 ){
                    $LabresultItem_type = "Mean";
                } elseif ($key == 1) {
                    $LabresultItem_type = "Standard deviation"; 
                } elseif ($key == 2) {
                    $LabresultItem_type = "C.O.V"; 
                } elseif ($key == 3) {
                    $LabresultItem_type = "Range"; 
                } elseif ($key == 4) {
                    $LabresultItem_type = "Number of readings"; 
                } elseif ($key == 5) {
                    $LabresultItem_type = "Min. reading"; 
                } elseif ($key == 6) {
                    $LabresultItem_type = "Max. reading"; 
                } 
                foreach ($value as $key2 => $value2) {
                    $insertLabResult = labresultitem::create([
                        "Labtransaction_id"=>$id_trx,
                        "Type"=>$LabresultItem_type,
                        "Var"=>$data2head[$key2],
                        "Val"=>$value2
                    ]);
                }
            }
            $dataReturn = [
                "success"=>true,
                "message"=>"Data Saved Succesfully",
                "LabTransactionID"=>$id_trx
            ];
            return $dataReturn;
        }else{
            $dataReturn = [
                "success"=>false,
                "message"=>"Failed to process. Invalid Option."
            ];
            return $dataReturn;
        }
    }

    private function GoldGlobalType($cleanedArrayData){
        $newdata = $cleanedArrayData;
        $data1 = [];
        $data2 = [];

        $operator = null;
        $kadar = null;
        $SWSPK = null;
        $measuringTime = null;
        $TestDate = null;

        // Data2Index'Au'
        $AuIndex = null;
        // TestQty
        $TestQty = 0;
        // Loop Cleaned Data
        foreach ($newdata as $key => $value) {
            // Check if Data index 0 is 'n' because 'n' is the first data
            if ($value[0] == 'n'){
                array_push($data1,$value);
                // Find "Au" Index
                foreach ($newdata as $key2 => $value2) {
                    if ($value2[0] == 'Au'){
                        $AuIndex = $key2;
                        break;
                    }
                }
                // Loop item after 'n' to before 'Au'
                for ($i=$key+1; $i < $AuIndex ; $i++) {
                    // Check if item its one line or two line
                    if (count($newdata[$i]) == 1) {
                        $temp = [];
                        $temp[] = $newdata[$i][0][0];
                        $restofit = substr($newdata[$i][0], 1);
                        $restofit = str_split($restofit, 6);
                        foreach ($restofit as $key => $value) {
                            $temp[] = $value;
                        }
                        array_push($data1,$temp);
                    }else{
                        $temp = [];
                        $temp[] = $newdata[$i][0];
                        $restofit = str_split($newdata[$i][1], 6);
                        foreach ($restofit as $key => $value) {
                            $temp[] = $value;
                        }
                        array_push($data1,$temp);
                    }
                }
            }
            // Check if Data index 0 is 'Au' because 'Au' is the second data
            if ($value[0] == 'Au'){
                array_push($data2, $value);
                for ($i=$key+1; $i < count($newdata) ; $i++) { 
                    // Check Mean
                    if ($newdata[$i][0] == 'Mean'){
                        $mean = $newdata[$i];
                        array_shift($mean);
                        array_push($data2, $mean);
                    }
                    // Check Standart Deviation
                    if ($newdata[$i][0] == 'Standard'){
                        $standardDeviation = $newdata[$i];
                        array_shift($standardDeviation);
                        array_shift($standardDeviation);
                        array_push($data2, $standardDeviation);
                    }
                    // Check COV
                    if ($newdata[$i][0] == 'C.O.V.'){
                        $COV = $newdata[$i];
                        array_shift($COV);
                        array_push($data2, $COV);
                    }
                    // Check Range
                    if ($newdata[$i][0] == 'Range'){
                        $range = $newdata[$i];
                        array_shift($range);
                        array_push($data2, $range);
                    }
                    // Check NumberOfReadings
                    if ($newdata[$i][0] == 'Number'){
                        $numberOfReadings = $newdata[$i];
                        array_shift($numberOfReadings);
                        array_shift($numberOfReadings);
                        array_shift($numberOfReadings);
                        $TestQty = $numberOfReadings[0];
                        array_push($data2, $numberOfReadings);
                    }
                    // Check MinReading
                    if ($newdata[$i][0] == 'Min.'){
                        $minReading = $newdata[$i];
                        array_shift($minReading);
                        array_shift($minReading);
                        array_push($data2, $minReading);
                    }
                    // Check MaxReading
                    if ($newdata[$i][0] == 'Max.'){
                        $maxReading = $newdata[$i];
                        array_shift($maxReading);
                        array_shift($maxReading);
                        array_push($data2, $maxReading);
                    }
                    // Check Operator
                    if (array_values($newdata[$i])[0] == 'Operator:'){
                        $operator = $newdata[$i][1];
                        $kadar = $newdata[$i][2];
                        $SWSPK = $newdata[$i][3];
                    }
                    if (array_values($newdata[$i])[0] == 'Measuring'){
                        $measuringTime = $newdata[$i][2];
                    }
                    if (array_values($newdata[$i])[0] == 'Date:'){
                        $TestDate = "".$newdata[$i][1]." ".$newdata[$i][3]." ".$newdata[$i][4];
                    }
                }
            }
        }
        $dt = DateTime::createFromFormat("d/m/Y H:i:s A",$TestDate)->getTimestamp();
        $TestDate = date('Y-m-d H:i:s',$dt);
        return [
            "testitem"=>$data1,
            "testresult"=>$data2,
            "operator"=>$operator,
            "kadar"=>$kadar,
            "SWSPK"=>$SWSPK,
            "TestQty"=>$TestQty,
            "type"=>2,
            "measuringTime"=>$measuringTime,
            "TestDate"=>$TestDate
        ];
    }

    private function Product26Type($cleanedArrayData){
        $newdata = $cleanedArrayData;
        
        $data1 = [];
        $data2 = [];
        
        $operator = null;
        $kadar = null;
        $SWSPK = null;
        $measuringTime = null;
        $TestDate = null;
        $TestQty = 0;
        // Loop Cleaned Data
        foreach ($newdata as $key => $value) {
            // Check if Data index 0 is 'n' because 'n' is the first data
            if (array_values($value)[0] == 'n'){
                // Getting the first data by its length. the length its always be the same.
                $lenghOfHeadD1 = count($value);
                foreach ($newdata as $key2 => $value2) {
                    if (count($value2) == $lenghOfHeadD1){
                        array_push($data1, array_values($value2));
                    }
                }
            }
            // Check if Data index 0 is 'Au' because 'Au' is the second data
            
            if (array_values($value)[0] == 'Au'){
                array_push($data2, array_values($value));
                for ($i=$key+1; $i < count($newdata) ; $i++) { 
                    // Check Mean
                    if (array_values($newdata[$i])[0] == 'Mean'){
                        $mean = array_values($newdata[$i]);
                        array_shift($mean);
                        array_push($data2, $mean);
                    }
                    // Check Standart Deviation
                    if (array_values($newdata[$i])[0] == 'Standard'){
                        $standardDeviation = array_values($newdata[$i]);
                        array_shift($standardDeviation);
                        array_shift($standardDeviation);
                        array_push($data2, $standardDeviation);
                    }
                    // Check COV
                    if (array_values($newdata[$i])[0] == 'C.O.V.'){
                        $COV = array_values($newdata[$i]);
                        array_shift($COV);
                        array_push($data2, $COV);
                    }
                    // Check Range
                    if (array_values($newdata[$i])[0] == 'Range'){
                        $range = array_values($newdata[$i]);
                        array_shift($range);
                        array_push($data2, $range);
                    }
                    // Check NumberOfReadings
                    if (array_values($newdata[$i])[0] == 'Number'){
                        $numberOfReadings = array_values($newdata[$i]);
                        array_shift($numberOfReadings);
                        array_shift($numberOfReadings);
                        array_shift($numberOfReadings);
                        $TestQty = $numberOfReadings[0];
                        array_push($data2, $numberOfReadings);
                    }
                    // Check MinReading
                    if (array_values($newdata[$i])[0] == 'Min.'){
                        $minReading = array_values($newdata[$i]);
                        array_shift($minReading);
                        array_shift($minReading);
                        array_push($data2, $minReading);
                    }
                    // Check MaxReading
                    if (array_values($newdata[$i])[0] == 'Max.'){
                        $maxReading = array_values($newdata[$i]);
                        array_shift($maxReading);
                        array_shift($maxReading);
                        array_push($data2, $maxReading);
                    }
                    // Check Operator
                    if (array_values($newdata[$i])[0] == 'Operator:'){
                        $operator = $newdata[$i][1];
                        $kadar = $newdata[$i][2];
                        $SWSPK = $newdata[$i][3];
                    }
                    if (array_values($newdata[$i])[0] == 'Measuring'){
                        $measuringTime = $newdata[$i][2];
                    }
                    if (array_values($newdata[$i])[0] == 'Date:'){
                        $TestDate = "".$newdata[$i][1]." ".$newdata[$i][3]." ".$newdata[$i][4];
                    }
                }
            }
        }
        $dt = DateTime::createFromFormat("d/m/Y H:i:s A",$TestDate)->getTimestamp();
        $TestDate = date('Y-m-d H:i:s',$dt);
        return [
            "testitem"=>$data1,
            "testresult"=>$data2,
            "operator"=>$operator,
            "kadar"=>$kadar,
            "SWSPK"=>$SWSPK,
            "TestQty"=>$TestQty,
            "type"=>1,
            "measuringTime"=>$measuringTime,
            "TestDate"=>$TestDate
        ];
    }

    public function ViewResult(){
        $Testid = 1;
        //!  ------------------------     Getting Lab Test Data     ------------------------ !!
        // Query All lab data
        $labtrx = labtransaction::with('LabTransactionItem','LabResultItem')->where('id',$Testid)->first();
        
        // Lab Test Item Container
        $datalabtestitem = [];
        // Append data by its variable to container
        foreach ($labtrx->LabTransactionItem as $key => $value) {
            $datalabtestitem[$value->Var][] = $value->Val;
        }
        //!  ------------------------     End Getting Lab Test Data     ------------------------ !!

        // dd($datalabtestitem);
        $datalabresultitem = [];
        foreach ($labtrx->LabResultItem as $key => $value) {
            $datalabresultitem[$value->Type][$value->Var] = $value->Val;
        }

        $operator = $labtrx['Operator'];
        $block = $labtrx['Block'];
        $SWSPK = $labtrx['SWSPK'];
        $kadar = $labtrx['Carat'];
        $measuringTime = $labtrx['MeasuringTime'];
        $date = $labtrx['TestDate'];

        // dd($datalabtestitem);
        // return response()->json($datalabresultitem,200);
        return view('LabTest.LabView',compact('datalabtestitem', 'datalabresultitem','operator','block','SWSPK','kadar','measuringTime','date'));
    }

    public function Index(){
        $labtrx = labtransaction::select('id','SWSPK')->orderBy('id','desc')->limit(10)->get();
        return view('LabTest.index',compact('labtrx'));
    }

    public function CheckLabTest(Request $request){
        $file = $request->file('file')->get();
        $result = $this->ProcessFile($file,'view');
        if ($result['success'] == true) {
            $datalabtestitem = $result['testitem'];
            $datalabresultitem = $result['testresult'];
            $operator = $result['operator'];
            $block = $result['block'];
            $SWSPK = $result['SWSPK'];
            $kadar = $result['kadar'];
            $measuringTime = $result['measuringTime'];
            $date = $result['TestDate'];
            $html = view('LabTest.LabView',compact('datalabtestitem', 'datalabresultitem','operator','block','SWSPK','kadar','measuringTime','date'))->render();
            return response()->json(["html"=>$html],200);
        }else {
            return response()->json($result,400);
        }
    }

    public function SaveLabTest(Request $request){
        $file = $request->file('file')->get();
        $result = $this->ProcessFile($file,'save');
        if ($result['success'] == true) {
            return response()->json($result,200);
        }else {
            return response()->json($result,400);
        }
    }

    public function CetakLabTest($keyword){
        $Testid = $keyword;
        //!  ------------------------     Getting Lab Test Data     ------------------------ !!
        // Query All lab data
        $labtrx = labtransaction::with('LabTransactionItem','LabResultItem')->where('id',$Testid)->first();
        if ($labtrx !== null){
            // Lab Test Item Container
            $datalabtestitem = [];
            // Append data by its variable to container
            foreach ($labtrx->LabTransactionItem as $key => $value) {
                $datalabtestitem[$value->Var][] = $value->Val;
            }
            //!  ------------------------     End Getting Lab Test Data     ------------------------ !!
    
            // dd($datalabtestitem);
            $datalabresultitem = [];
            foreach ($labtrx->LabResultItem as $key => $value) {
                $datalabresultitem[$value->Type][$value->Var] = $value->Val;
            }
    
            $operator = $labtrx['Operator'];
            $block = $labtrx['Block'];
            $SWSPK = $labtrx['SWSPK'];
            $kadar = $labtrx['Carat'];
            $measuringTime = $labtrx['MeasuringTime'];
            $date = $labtrx['TestDate'];
    
            // dd($datalabtestitem);
            // return response()->json($datalabresultitem,200);
            return view('LabTest.cetak',compact('datalabtestitem', 'datalabresultitem','operator','block','SWSPK','kadar','measuringTime','date'));
        }else{
            abort(404);
        }
        
    }

    public function SearchLabTest($keyword){
        $Testid = $keyword;
        //!  ------------------------     Getting Lab Test Data     ------------------------ !!
        // Query All lab data
        $labtrx = labtransaction::with('LabTransactionItem','LabResultItem')->where('id',$Testid)->first();
        if ($labtrx !== null) {
            // Lab Test Item Container
            $datalabtestitem = [];
            // Append data by its variable to container
            foreach ($labtrx->LabTransactionItem as $key => $value) {
                $datalabtestitem[$value->Var][] = $value->Val;
            }
            //!  ------------------------     End Getting Lab Test Data     ------------------------ !!
    
            // dd($datalabtestitem);
            $datalabresultitem = [];
            foreach ($labtrx->LabResultItem as $key => $value) {
                $datalabresultitem[$value->Type][$value->Var] = $value->Val;
            }
    
            $operator = $labtrx['Operator'];
            $block = $labtrx['Block'];
            $SWSPK = $labtrx['SWSPK'];
            $kadar = $labtrx['Carat'];
            $measuringTime = $labtrx['MeasuringTime'];
            $date = $labtrx['TestDate'];
    
            // dd($datalabtestitem);
            $html = view('LabTest.LabView',compact('datalabtestitem', 'datalabresultitem','operator','block','SWSPK','kadar','measuringTime','date'))->render();
            return response()->json(['html'=>$html,"LabTransactionID"=>$labtrx['id']],200);
        } else{
            return response()->json(["message"=>"Lab with that transaction id not found.",'success'=>false],404);
        }
        
    }
}
