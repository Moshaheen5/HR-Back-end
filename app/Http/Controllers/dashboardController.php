<?php

namespace App\Http\Controllers;

use App\Models\attendance;
use App\Models\employee;
use App\Models\group;
use App\Models\User;



class dashboardController extends Controller
{
    public function getCounts(){
        $totalEmployees=employee::count();
        $totalUsers=User::count();
        $totalAttendanceReps=attendance::count();
        $totalgroups=group::count();
        return response()->json([
            'totalemployees'=>$totalEmployees,
            'totalusers'=>$totalUsers,
            'totalreports'=>$totalAttendanceReps,
            'totalgroups'=>$totalgroups,
        ]);

    }
}
