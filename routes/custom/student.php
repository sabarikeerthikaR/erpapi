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

// parent

    Route::group(['prefix' => 'parent'], function () {

        Route::group(["middleware"=>["auth:api","auth.student"]], function () {
    //switch student

    route::post('switchstudent','school\AdminController@switchStudent');
        route::get('listStudents','school\AdminController@listStudents');
    //student dashboard
    route::get('dashboardstudent','school\DashboardController@dashboard');
    route::get('getweeklyattendance','school\DashboardController@getweeklyattendance');
    route::get('getMonthlyAttendance','school\DashboardController@getMonthlyAttendance');

    //student
    route::get('studentProfileStudent', 'school\AdmissionController@studentProfileStudent');
    route::get('StudentmyIdCard', 'school\AdmissionController@StudentmyIdCard');
    route::get('StudentLeavingCart', 'school\AdmissionController@StudentLeavingCart');
    route::get('StudentMyCertificate', 'school\AdmissionController@StudentMyCertificate');
    route::get('StudentbirthCertificate', 'school\AdmissionController@StudentbirthCertificate');
    route::get('StudentmyHisory', 'school\AdmissionController@StudentmyHisory');
    route::get('StudentViewFeeStatement', 'school\AdmissionController@StudentViewFeeStatement');

    //student attendance
    route::get('studentMyAttendance','school\ClassAttendanceController@studentMyAttendance');

    //student timetable
    route::get('studentMyTimetable','school\StudentTimetableController@studentMyTimetable');

    //my staff
    route::get('classTeacher', 'school\AdmissionController@classTeacher');

    //assignment
    route::get('AssignmetShowStudent','school\AssignmentController@AssignmetShowStudent');

    ///syllabus
    route::get('studentselectsyllabus','school\SyllabusController@studentselectsyllabus');


    //past paper
    route::get('studentshowPastPaper','school\PastPaperController@studentshowPastPaper');


    //sms
    route::get('selectStaffForMessage','school\SmsController@selectStaffForMessage');
    route::post('MessageTeacher','school\SmsController@MessageTeacher');
    route::get('incommingMessage','school\SmsController@incommingMessage');
    route::get('outGoingMessage','school\SmsController@outGoingMessage');
    route::post('messageReplay','school\SmsController@messageReplay');

        //notice board
        route::get('teachernoticeBoard','school\NoticeBoardController@teachernoticeBoard');
    
    // exam
    route::get('termForExam','school\ExamController@termForExam');
    route::get('viewExamTimetableStudent','school\ExamController@viewExamTimetableStudent');
       route::get('ExamResults','school\ExamController@ExamResults');

    //fee payments

    route::post('OnlinePaymentpost','school\OnlinePaymentController@OnlinePaymentpost');
    route::get('FeeStatement','school\OnlinePaymentController@FeeStatement');
route::get('OnlinePaymentTermFeeList','school\OnlinePaymentController@OnlinePaymentTermFeeList');
route::get('feeExtrass','school\OnlinePaymentController@feeExtrass');
route::post('FeeExtraspost','school\OnlinePaymentController@FeeExtraspost');
route::get('FeeExtrassStatement','school\OnlinePaymentController@FeeExtrassStatement');

    });
    });


    Route::get('testCode', function () {
        dd(config('jetstream.middleware', ['web']));
    });