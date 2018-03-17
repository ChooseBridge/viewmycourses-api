<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    //
    public function getStudent()
    {

        $student =[
            'name'=>$GLOBALS['gStudent']->name,
            'email'=>$GLOBALS['gStudent']->email,
        ];
        $data = [
          'success' => true,
          'data' => $student
        ];

        return \Response::json($data);

    }
}
