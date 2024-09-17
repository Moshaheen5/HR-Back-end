<?php

namespace App\Http\Controllers;
use App\Models\weekend;

use Illuminate\Http\Request;

class WeekendController extends Controller
{
    public function index(){
        $weekends=weekend::get();
        return response()->json(['weekends'=>$weekends]);
    }
   
     public function store(Request $request){
        $weekend= $request->validate([
            'name'=>'required|string|max:255'
        ]);
   
        weekend::create($request->all());
        return response()->json([
            'message' => 'Weekend added successfully',
            'recently added'=>$weekend,201],
            );
    }
    public function show(weekend $weekend){
     
        $weekend=weekend::find($weekend);
        return response()->json(['weekend'=>$weekend]);
    }

     public function update(Request $request,weekend $weekend){
        $request->validate([
            'name'=>'required|string|max:255'
        ]);
   
        $weekend->update($request->all());
        return response()->json([
            'message' => 'Weekend updated successfully',
            'recently updated'=>$weekend,201],
            );
    }
     public function destroy(weekend $weekend){
        $weekend->delete();
        return response()->json([
            'message'=>'Weekend deleted successfully',
            'recently deleted'=>$weekend,
             201]);
    
     }
}
