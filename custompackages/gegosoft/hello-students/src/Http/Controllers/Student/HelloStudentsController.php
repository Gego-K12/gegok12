<?php

namespace Gegosoft\HelloStudents\Http\Controllers\Student;

use App\Http\Controllers\Controller;

class HelloStudentsController extends Controller
{
    public function index()
    {
        return view('student/hello-students/index');
    }
}
