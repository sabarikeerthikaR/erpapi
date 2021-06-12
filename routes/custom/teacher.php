<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//teacher module
Route::group(['prefix' => 'teacher'], function () {

    Route::group(["middleware"=>["auth:api","auth.teacher"]], function () {

    //dashboard
    route::get('teacherDashBoard','school\DashboardController@teacherDashBoard');

    //teaching staff
    route::get('teachingstafffullprofile','school\StaffController@teachingstafffullprofile');
    route::get('teachingstaffprofile','school\StaffController@teachingstaffprofile');


    //employee attendance
        route::get('EmployeeMyAttendance','school\EmployeeAttendanceController@EmployeeMyAttendance');

    //teacher timetable
    route::get('myTimetable','school\TeacherTimetableController@myTimetable');


       
    //student attendance
    route::post('studentAtten_storeforteacher','school\ClassAttendanceController@storeforteacher');
    route::get('StudentByclass','school\ClassAttendanceController@StudentByclass');
    route::get('classAttendanceViewforteacher','school\ClassAttendanceController@classAttendanceViewforteacher');
    route::get('AttendanceListByDate','school\ClassAttendanceController@AttendanceListByDate');
    
    // student
    route::get('myclass','school\TeacherTimetableController@myclass');
    route::get('listStudent','school\SubjectController@listStudent');
    route::get('studentPro','school\AdmissionController@studentProfile');
    
    //assignment
    route::post('assignmentstoreTeacher','school\AssignmentController@assignmentstoreTeacher');
    route::get('AssignmetShowTeacher','school\AssignmentController@AssignmetShowTeacher');
    route::get('assignmentviewteacher','school\AssignmentController@assignmentviewteacher');
    
     ///syllabus
     route::post('teachermakeSyllabus','school\SyllabusController@teachermakeSyllabus');
     route::get('teacherselectsyllabus','school\SyllabusController@teacherselectsyllabus');


         //past paper

    route::get('teachershowPastPaper','school\PastPaperController@teachershowPastPaper');
   
        // exam
        route::get('examReport','school\ExamController@examReport');
        route::get('examReportView','school\ExamController@examReportView');
        route::get('teacherMarkEntry','school\ExamController@teacherMarkEntry');
        route::post('teacherMarkEntryPost','school\ExamController@teacherMarkEntryPost');
        route::get('teacherExams','school\ExamController@teacherExams');

     //sms
    route::post('teacherSms','school\SmsController@teacherSms');
    route::get('showteacherSms','school\SmsController@showteacherSms');
    
     //leave
     route::get('showteacherLeaveRequest','school\SmsController@showteacherLeaveRequest');
     route::post('teacherLeaveRequest','school\SmsController@teacherLeaveRequest');
     

 
    //notice board
    route::get('teachernoticeBoard','school\NoticeBoardController@teachernoticeBoard');
    
    
    });
    
});


Route::get('testCode', function () {
    dd(config('jetstream.middleware', ['web']));
});