<?php

namespace App\Http\Controllers;
use App\Models\attendance;
use App\Models\Holiday;
use App\Models\weekend;
use Carbon\Carbon;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\String_;
use PhpParser\Node\Expr\FuncCall;

class AttendanceController extends Controller
{


    public function index(){
        $attendanceList=attendance::get();
        
       
        return response()->json(['data'=>$attendanceList,201]);
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'weekend_id' => 'nullable',
            'holiday_id' => 'nullable',
            'check_in' => 'required|date_format:H:i:s',
            'check_out' => 'required|date_format:H:i:s',
            'date' => 'required|date_format:Y-m-d',
        ]);
    
        
            $check_in = Carbon::parse($validated['check_in']);
            $check_out = Carbon::parse($validated['check_out']);
            $hours = $check_out->diffInHours($check_in);
    
            $status=$this->getStatus($hours);

        $validated['weekend_id']= $this->isweekend($validated['date']);
        $validated['holiday_id']= $this->isholiday($validated['date']);
        
            if ($validated['weekend_id']  ) {
            return response()->json([
                'message'=>'No attendance recorded on weekends.',200
            ]) ; 
                } elseif ($validated['holiday_id'] ) {
                    return response()->json([
                        'message'=>'No attendance recorded on Holidays.',200
                    ]) ; 
                        }
                  
                $attendance = Attendance::create([
                    'employee_id' => $validated['employee_id'],
                    'weekend_id' => $validated['weekend_id'],
                    'holiday_id' => $validated['holiday_id'],
                    'check_in' => $check_in,
                    'check_out' => $check_out,
                    'date' => $validated['date'],
                    'hours' => $hours,
                    'status' => $status,
                ]);
                
                //check hours for slalry action
            $empsalary = $attendance->employee->salary;
            $workHours=8;
        
   
            if($hours != $workHours){ 
                $salaryAction=  $this->getSalaryAction($hours,$empsalary,$workHours);
            
       
            // create salary action
        
             $attendance->salaryAction()->create([
            'employee_id' => $validated['employee_id'],
            'attendance_id' => $attendance->id,
            'date' => $validated['date'],
            'type' =>$salaryAction['type'] ,
            'amount' =>$salaryAction['amounts'] ,
            'hours' =>$salaryAction['rewardHours'] ,
            'details' =>$salaryAction['description'] ,
            'created_at' => now(),
            'updated_at' => now(),
        ]);}
        // json response
        return response()->json([
            'message' => 'Attendance added successfully',
            'attendance' => $attendance,
            'hours' => $hours,
            'status' => $status,
        ], 201);
    }
    
    public function show(attendance $attendance){
        $attendance= attendance::find($attendance);
        return response()->json([
                'message'=>'attendance',
                'attendance'=>$attendance,
                201
            ]);
        
    }

    public function update(Request $request,attendance $attendance){
        $validated=$request->validate([
            'employee_id'=>'required|exists:employees,id',
            'weekend_id'=>'nullable',
            'holiday_id'=>'nullable',
            'check_in'=>'required|date_format:H:i:s',
            'check_out'=>'required|date_format:H:i:s', 
            'date'=>'required|date_format:Y-m-d', 
          
        ]);

        $check_in=new Carbon($validated['check_in']);
        $check_out=new Carbon($validated['check_out']);
        $hours=$check_out->diffInHours($check_in);

        //check for status

        if($hours==0){
            $status='absent';
        }
        elseif($hours==8){
            $status='present';
        }
        elseif($hours>8){
            $status='bonus';
        }else{
            $status='deduction';
        }



        $attendance->update([
            'employee_id'=>$validated['employee_id'],
            'weekend_id'=>$validated['weekend_id'],
            'holiday_id'=>$validated['holiday_id'],
            'check_in'=>$check_in,
            'check_out'=>$check_out,
            'date'=>$validated['date'],
            'hours'=>$hours,
            'status'=>$status,
        ]);

        $empsalary = $attendance ? $attendance->employee->salary : 0;

      if($hours !=8)
      {
     
        $types='';
        $rewordHoures=0;
        $Amounts=0;
        $discript="";
      
        
        if($hours>8){
             $types="bonus";
             $rewordHoures=$hours-8;
             $Amounts=(($empsalary/30)/8)*$rewordHoures;
             $discript="Bouns Houres Added ";

        }
        elseif($hours<8)
        {
            $types="deduction";
            $rewordHoures=8-$hours;
            $Amounts=(($empsalary/22)/8)*$rewordHoures;
            $discript="deduction Houres Added ";
        }
      
        $attendance -> salaryAction()->update([
            'employee_id' => $validated['employee_id'],
            'attendance_id' => $attendance->id,
            'date' => $validated['date'],
            'type' => $types,
            'amount' => $Amounts,
            'hours' => $rewordHoures,
            'details' => $discript,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
      }





        return response()->json([
            'message'=>'attendance updated successfully',
            'attendance'=>$attendance,
            'hours'=>$hours,
            'status'=>$status,
            201]) ;



    }
    public function destroy(attendance $attendance){
        $attendance->delete();
        return response()->json([
            'message'=>'attendance deleted successfully',
            
             201]);
        

    }

    private function getStatus($hours){

        if($hours==0){
            return 'absent';
        }
        elseif($hours==8){
             return 'present';
        }
        elseif($hours>8){
             return 'bonus';
        }else{
             return 'deduction';
        }
    }

    private function getSalaryAction($hours,$empsalary,$workHours){
                     $types = '';
                    $rewardHours = 0;
                    $amounts = 0;
                    $description = '';
                
                    if ($hours > $workHours) {
                        $types = 'bonus';
                        $rewardHours = $hours - $workHours;
                        $amounts = (($empsalary / 30) / $workHours) * $rewardHours; 
                        $description = 'Bonus hours added';
                    } elseif ($hours < $workHours && $hours > 0) {
                        $types = 'deduction';
                        $rewardHours = $workHours - $hours;
                        $amounts = (($empsalary / 22) / $workHours) * $rewardHours; 
                        $description = 'Deduction hours added';
                    }

                return [
                    'type' => $types,
                    'rewardHours'=> $rewardHours,
                    'amounts' => $amounts,
                    'description'=>$description
                ];
    }


    private Function isweekend  ($date){
        $dayOfWeek = Carbon::parse($date)->format('l'); 
        $weekend = weekend::where('name',$dayOfWeek)->first();     
            return $weekend ? $weekend->id : null;
          
    }
    private Function isholiday  ($date){
        $dayOfWeek = Carbon::parse($date)->format('l'); 
        $holiday = Holiday::where('holiday_date',$date)->first();     
           return $holiday ?$holiday->id :null;
          
    }
}