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

//Route::group(["middleware"=>["auth:api","auth.student"]], function () {
//switch student

route::post('switchstudent','school\AdminController@switchStudent');
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
route::get('studentAttenCalenderView','school\ClassAttendanceController@studentAttenCalenderView');

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
route::post('sms_store','school\SmsController@store');
route::get('sms_show','school\SmsController@index');
route::post('sms_update','school\SmsController@update');
route::get('sms_destroy','school\SmsController@destroy');
route::get('sms_select','school\SmsController@show');
route::get('listStudentsms','school\SmsController@listStudent');
route::post('customSms','school\SmsController@customSms');
route::post('SmsRandomNo','school\SmsController@SmsRandomNo');
route::get('MessageFeedback','school\SmsController@MessageFeedback');
route::post('leaveRequest','school\SmsController@leaveRequest');






// exam
route::post('exm_store','school\ExamController@store');
route::get('exm_show','school\ExamController@index');
route::post('exm_update','school\ExamController@update');
route::get('exm_destroy','school\ExamController@destroy');
route::get('exm_select','school\ExamController@show');
route::post('markEntry','school\ExamController@markEntry');
route::get('ExamMarkView','school\ExamController@ExamMarkView');
route::get('ExamCertificate','school\ExamController@ExamCertificate');
route::get('ExamCertificateview','school\ExamController@ExamCertificateview');
route::post('ExamCertificateedit','school\ExamController@ExamCertificateedit');
route::get('ExamCertificateselect','school\ExamController@ExamCertificateselect');
route::post('examTimetable','school\ExamController@examTimetable');
route::get('ExamResults','school\ExamController@ExamResults');

//fee payments
route::post('feePayment_store','school\FeePaymentController@store');
route::get('feePayment_show','school\FeePaymentController@index');
route::post('feePayment_update','school\FeePaymentController@update');
route::get('feePayment_destroy','school\FeePaymentController@destroy');
route::get('feePayment_select','school\FeePaymentController@show');
route::get('feePaymentStatusview','school\FeePaymentController@feePaymentStatusview');
route::get('feePaymentStatusdelete','school\FeePaymentController@feePaymentStatusdelete');
route::get('feePaymentlist','school\FeePaymentController@feePaymentlist');
route::post('feePaymentlistedit','school\FeePaymentController@feePaymentlistedit');
route::get('feePaymentlistdelete','school\FeePaymentController@feePaymentlistdelete');
route::post('OnlinePayment','school\FeePaymentController@OnlinePayment');
route::get('FeeStatement','school\FeePaymentController@FeeStatement');

});

//});


Route::get('testCode', function () {
    dd(config('jetstream.middleware', ['web']));
});