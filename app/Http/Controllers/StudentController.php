<?php

namespace App\Http\Controllers;

use App\Service\Abstracts\StudentServiceAbstract;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    protected $studentService;

    public function __construct(StudentServiceAbstract $studentService)
    {
        $this->studentService = $studentService;
    }

//api
    public function getStudent()
    {

        $student = [
          'name' => $GLOBALS['gStudent']->name,
          'email' => $GLOBALS['gStudent']->email,
        ];
        $data = [
          'success' => true,
          'data' => $student
        ];

        return \Response::json($data);

    }


    public function setPoints()
    {
        $delta = $_GET['delta'];
        $data = $this->studentService->setPoints($delta);
        var_dump($data);
    }

    public function getPoints()
    {
        $data = $this->studentService->getPoints();
        var_dump($data);
    }

}
