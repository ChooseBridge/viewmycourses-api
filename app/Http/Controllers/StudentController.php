<?php

namespace App\Http\Controllers;

use App\ProfessorRate;
use App\SchoolRate;
use App\Service\Abstracts\ProfessorRateServiceAbstract;
use App\Service\Abstracts\SchoolRateServiceAbstract;
use App\Service\Abstracts\StudentServiceAbstract;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    protected $studentService;
    protected $professorRateService;
    protected $schoolRateService;

    public function __construct(
      StudentServiceAbstract $studentService,
      ProfessorRateServiceAbstract $professorRateService,
      SchoolRateServiceAbstract $schoolRateService
    ) {
        $this->studentService = $studentService;
        $this->professorRateService = $professorRateService;
        $this->schoolRateService = $schoolRateService;
    }

//api
    public function getStudent()
    {

        $GLOBALS['gStudent'] = $this->studentService->getStudentByToken('add005a2611d120c1099fb1cd1fbb176');
        $student = [
          'name' => $GLOBALS['gStudent']->name,
          'email' => $GLOBALS['gStudent']->email,
        ];

        $professorRates = $this->professorRateService->getRatesByStudentId($GLOBALS['gStudent']->student_id);
        $professorRatesInfo = [];
        foreach ($professorRates as $rate) {
            $professorRatesInfo["$rate->created_at"] = [
              'rate_type' => 'professor',
              'school_name' => $rate->school->school_name,
              'professor_name' => $rate->professor->professor_name,
              'course_code' => $rate->course_code,
              'course_name' => $rate->course_name,
              'course_category_name' => $rate->course_category_name,
              'is_attend' => $rate->is_attend,
              'difficult_level' => $rate->difficult_level,
              'homework_num' => $rate->homework_num,
              'quiz_num' => $rate->quiz_num,
              'course_related_quiz' => $rate->course_related_quiz,
              'spend_course_time_at_week' => $rate->spend_course_time_at_week,
              'grade' => $rate->grade,
              'comment' => $rate->comment,
              'tag' => $rate->tag,
              'effort' => $rate->effort,
            ];
        }


        $schoolRates = $this->schoolRateService->getRatesByStudentId($GLOBALS['gStudent']->student_id);
        $schoolRatesInfo = [];
        foreach ($schoolRates as $rate) {
            $schoolRatesInfo["$rate->created_at"] = [
              'rate_type' => 'school',
              'school_district_name' => $rate->schoolDistrict->school_district_name,
              'school_name' => $rate->school->school_name,
              'social_reputation' => $rate->social_reputation,
              'academic_level' => $rate->academic_level,
              'network_services' => $rate->network_services,
              'accommodation' => $rate->accommodation,
              'food_quality' => $rate->food_quality,
              'campus_location' => $rate->campus_location,
              'extracurricular_activities' => $rate->extracurricular_activities,
              'campus_infrastructure' => $rate->campus_infrastructure,
              'life_happiness_index' => $rate->life_happiness_index,
              'school_students_relations' => $rate->school_students_relations,
              'comment' => $rate->comment,
              'student_name' => $rate->student->name,
              'score' => $rate->score,
            ];
        }

        $ratesInfo = $schoolRatesInfo + $professorRatesInfo;
        krsort($ratesInfo);


        $data = [
          'success' => true,
          'data' => [
            'student'=>$student,
            'ratesInfo'=>$ratesInfo,
          ]
        ];

        return \Response::json($data);

    }


    public function setPoints()
    {
        $delta = $_GET['delta'];
        $data = $this->studentService->setPoints($delta,'11',$GLOBALS['gStudent']);
        var_dump($data);
    }

    public function getPoints()
    {
        $data = $this->studentService->getPoints($GLOBALS['gStudent']);
        var_dump($data);
    }

}
