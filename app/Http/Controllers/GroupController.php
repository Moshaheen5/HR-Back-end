<?php

namespace App\Http\Controllers;
use App\Models\group;

use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(){
        $groups=group::with('privileges')->get();
        return response()->json([
            'groups'=>$groups,],200);

    }
    
}
