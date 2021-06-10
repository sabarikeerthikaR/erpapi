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





            Route::group(["middleware"=>["auth:api","auth.admin"]], function () {
            route::get('list-parents', 'school\AuthController@destroy');
            route::post('register','school\AuthController@register');
                route::get('login_list', 'school\AuthController@index');
            route::post('update', 'school\AuthController@update');
            route::get('destroy', 'school\AuthController@destroy');

            route::post('notification', 'school\AuthController@notification');

            //profile
            route::get('profile', 'school\AuthController@profile');

            //allstudents
            route::get('list_students', 'school\AdmissionController@allstudent');
            route::post('b_day', 'school\AdmissionController@birthday');
            route::get('studentProfile', 'school\AdmissionController@studentProfile');
            route::get('myIdCard', 'school\AdmissionController@myIdCard');
            route::get('LeavingCart', 'school\AdmissionController@LeavingCart');
            route::get('MyCertificate', 'school\AdmissionController@MyCertificate');
            route::get('birthCertificate', 'school\AdmissionController@birthCertificate');
            route::get('myHisory', 'school\AdmissionController@myHisory');
            route::get('ViewFeeStatement', 'school\AdmissionController@ViewFeeStatement');


            route::get('myStaff', 'school\AdmissionController@myStaff');

            //add to extracurricular activities
            route::get('studentlistactivity', 'school\AddExcActivitiesController@liststudentactivity');
            route::post('addstudenToAct', 'school\AddExcActivitiesController@addstudenToAct');
            route::get('listcurricular', 'school\AddExcActivitiesController@extraCurricularAct');

            //add to transport route

            route::get('liststudentroute', 'school\TransportController@liststudentroute');
            route::post('addstudenToRoute', 'school\TransportController@addstudenToRoute');
            route::get('listTransport', 'school\TransportController@listTransport');

            //suspend
            route::get('inactive', 'school\EmergencyContactController@inactive');
            route::post('suspend', 'school\EmergencyContactController@Suspend');
            route::get('all_suspend', 'school\EmergencyContactController@allsuspended');
            route::post('activate', 'school\EmergencyContactController@activate');

            //alumni
            route::get('Alumnistudent','school\AdmissionController@Alumnistudent');
            route::get('ActivateAlumni','school\AdmissionController@ActivateAlumni');

            //admission
            route::post('admit','school\AdmissionController@admit');
            route::post('admission_store','school\AdmissionController@store');
            route::get('admission_show','school\AdmissionController@index');
            route::post('admission_update','school\AdmissionController@update');
            route::get('admission_destroy','school\AdmissionController@destroy');
            route::get('admission_select','school\AdmissionController@show');

            //teaching staff
            route::post('staff_store','school\StaffController@store');
            route::get('staff_show','school\StaffController@index');
            route::post('staff_update','school\StaffController@update');
            route::get('staff_destroy','school\StaffController@destroy');
            route::get('staff_select','school\StaffController@show');
            route::post('staff_disable','school\StaffController@disable');
            route::post('staff_enable','school\StaffController@enable');
            route::get('staff_inactive','school\StaffController@inactive');

            //non teaching staff
            route::post('nonteachingstore','school\NonTeachingController@store');
            route::get('nonteachingshow','school\NonTeachingController@index');
            route::post('nonteachingupdate','school\NonTeachingController@update');
            route::get('nonteachingdestroy','school\NonTeachingController@destroy');
            route::get('nonteachingselect','school\NonTeachingController@show');
            route::post('nonteaching_disable','school\NonTeachingController@disable');
            route::post('nonteaching_enable','school\NonTeachingController@enable');
            route::get('nonteaching_inactive','school\NonTeachingController@inactive');

            //supporting teaching staff
            route::post('supportingStaffstore','school\SupportingStaffController@store');
            route::get('supportingStaffshow','school\SupportingStaffController@index');
            route::post('supportingStaffupdate','school\SupportingStaffController@update');
            route::get('supportingStaffdestroy','school\SupportingStaffController@destroy');
            route::get('supportingStaffgselect','school\SupportingStaffController@show');
            route::post('supporting_disable','school\SupportingStaffController@disable');
            route::post('supporting_enable','school\SupportingStaffController@enable');
            route::get('supporting_inactive','school\SupportingStaffController@inactive');

            //1st parent
            route::post('parentf_store','school\First_parentController@store');
            route::get('parentf_show','school\First_parentController@index');
            route::post('parentf_update','school\First_parentController@update');
            route::get('parentf_destroy','school\First_parentController@destroy');
            route::get('parentf_select','school\First_parentController@show');
            route::get('profileparent','school\First_parentController@profile');


            //emergency contacts
            route::post('eme_store','school\EmergencyContactController@store');
            route::get('eme_show','school\EmergencyContactController@index');
            route::post('eme_update','school\EmergencyContactController@update');
            route::get('eme_destroy','school\EmergencyContactController@destroy');
            route::get('eme_select','school\EmergencyContactController@show');

            //admission details
            route::post('adde_store','school\AdmissionDetailsController@store');
            route::get('adde_show','school\AdmissionDetailsController@index');
            route::post('adde_update','school\AdmissionDetailsController@update');
            route::get('adde_destroy','school\AdmissionDetailsController@destroy');
            route::get('adde_select','school\AdmissionDetailsController@show');

            //class
            route::post('clas_store','school\StdClassController@store');
            route::get('clas_show','school\StdClassController@index');
            route::post('clas_update','school\StdClassController@update');
            route::post('clas_stream','school\StdClassController@add_stream');
            route::get('clas_destroy','school\StdClassController@destroy');
            route::get('clas_select','school\StdClassController@show');
            route::get('classsetting','school\StdClassController@classsetting');
            route::post('disablClass','school\StdClassController@disablClass');
            route::post('enabelClass','school\StdClassController@enabelClass');
            route::get('allclass','school\StdClassController@allclass');
            route::post('addteacher','school\StdClassController@addteacher');
            route::get('selectstreamclass','school\StdClassController@selectAllClassStream');
            route::get('classStreamView','school\StdClassController@classStreamView');


            //student dashboard
            route::get('admindashboard','school\DashboardController@admindashboard');
            route::get('activitiesAct','school\DashboardController@activitiesAct');


            //house
            route::post('hou_store','school\StudentHouseController@store');
            route::get('hou_show','school\StudentHouseController@index');
            route::post('hou_update','school\StudentHouseController@update');
            route::get('hou_destroy','school\StudentHouseController@destroy');
            route::get('hou_select','school\StudentHouseController@show');

            //stream
            route::post('stm_store','school\ClassStreamController@store');
            route::get('stm_show','school\ClassStreamController@index');
            route::post('stm_update','school\ClassStreamController@update');
            route::get('stm_destroy','school\ClassStreamController@destroy');
            route::get('stm_select','school\ClassStreamController@show');

            //staff list
            route::get('teaching','school\StaffController@teaching');
            route::get('nonteaching','school\StaffController@nonteaching');
            route::get('supporting','school\StaffController@supporting');

            //board_members
            route::post('bm_store','school\BoardMembersController@store');
            route::get('bm_show','school\BoardMembersController@index');
            route::post('bm_update','school\BoardMembersController@update');
            route::get('bm_destroy','school\BoardMembersController@destroy');
            route::get('bm_select','school\BoardMembersController@show');
            route::post('boardmem_disable','school\BoardMembersController@disable');
            route::post('boardmem_enable','school\BoardMembersController@enable');
            route::get('boardmem_profile','school\BoardMembersController@profile');
            route::get('boardmem_inactive','school\BoardMembersController@inactive');

            //student_bulk_clearence
            route::post('stub_store','school\StudentBulkClearanceController@store');
            route::get('stub_show','school\StudentBulkClearanceController@index');
            route::post('stub_update','school\StudentBulkClearanceController@update');
            route::get('stub_destroy','school\StudentBulkClearanceController@destroy');
            route::get('stub_select','school\StudentBulkClearanceController@show');
            route::get('viewStudentClearance','school\StudentBulkClearanceController@view');

            //staff_bulk_clearence
            route::post('stab_store','school\StafBulkClearanceController@store');
            route::get('stab_show','school\StafBulkClearanceController@index');
            route::post('stab_update','school\StafBulkClearanceController@update');
            route::get('stab_destroy','school\StafBulkClearanceController@destroy');
            route::get('stab_select','school\StafBulkClearanceController@show');

            //student clearance
            route::post('studentb_store','school\StudentClearanceController@store');
            route::get('studentb_show','school\StudentClearanceController@index');
            route::post('studentb_update','school\StudentClearanceController@update');
            route::get('studentb_destroy','school\StudentClearanceController@destroy');
            route::get('studentbb_select','school\StudentClearanceController@show');

            //staff clearance// bulk 
            route::post('staffc_store','school\StaffClearanceController@store');
            route::get('staffc_show','school\StaffClearanceController@index');
            route::post('staffc_update','school\StaffClearanceController@update');
            route::get('staffc_destroy','school\StaffClearanceController@destroy');
            route::get('staffc_select','school\StaffClearanceController@show');
            route::get('StaffBulkClearance','school\StaffClearanceController@view');

            //user group
            route::post('group_store','school\UserGroupController@store');
            route::get('group_show','school\UserGroupController@index');
            route::post('group_update','school\UserGroupController@update');
            route::get('group_destroy','school\UserGroupController@destroy');
            route::get('listuser', 'school\AuthController@show');
            route::get('selectuser', 'school\AuthController@selectuser');
            route::get('group_select','school\UserGroupController@show');

            //group gtaff
            route::post('groupStaff_store','school\Group_staffController@store');
            route::get('groupStaff_show','school\Group_staffController@index');
            route::post('groupStaff_update','school\Group_staffController@update');
            route::get('groupStaff_destroy','school\Group_staffController@destroy');
            route::get('groupStaff_select','school\Group_staffController@show');

            //contracts
            route::post('contracts_store','school\ContractsController@store');
            route::get('contracts_show','school\ContractsController@index');
            route::post('contracts_update','school\ContractsController@update');
            route::get('contracts_destroy','school\ContractsController@destroy');
            route::get('contracts_select','school\ContractsController@show');

            //department
            route::post('department_store','school\DepartmentController@store');
            route::get('department_show','school\DepartmentController@index');
            route::post('department_update','school\DepartmentController@update');
            route::get('department_destroy','school\DepartmentController@destroy');
            route::get('department_select','school\DepartmentController@show');


            //past paper
            route::post('pap_store','school\PastPaperController@store');
            route::get('pap_show','school\PastPaperController@index');
            route::post('pap_update','school\PastPaperController@update');
            route::get('pap_destroy','school\PastPaperController@destroy');
            route::get('pap_select','school\PastPaperController@show');

            //folder
            route::post('fold_store','school\FolderController@store');
            route::get('fold_show','school\FolderController@index');
            route::post('fold_update','school\FolderController@update');
            route::get('fold_destroy','school\FolderController@destroy');
            route::get('fold_select','school\FolderController@show');

            //book category
            route::post('bc_store','school\BooksCategoryController@store');
            route::get('bc_show','school\BooksCategoryController@index');
            route::post('bc_update','school\BooksCategoryController@update');
            route::get('bc_destroy','school\BooksCategoryController@destroy');
            route::get('bc_select','school\BooksCategoryController@show');

            //books
            route::post('book_store','school\BooksController@store');
            route::get('book_show','school\BooksController@index');
            route::post('book_update','school\BooksController@update');
            route::get('book_destroy','school\BooksController@destroy');
            route::get('book_select','school\BooksController@show');
            route::get('manageStock','school\BooksController@manageStock');
            route::post('addStock','school\BooksController@addStock');
            route::post('deleteStock','school\BooksController@deleteStock');
            route::get('bookProfile','school\BooksController@bookProfile');

            //borrow
            route::post('brw_store','school\BorrowController@store');
            route::get('brw_show','school\BorrowController@index');
            route::post('brw_update','school\BorrowController@update');
            route::get('brw_destroy','school\BorrowController@destroy');
            route::get('brw_select','school\BorrowController@show');
            route::get('returnBook','school\BorrowController@returnBook');
            route::post('addReturn','school\BorrowController@addReturn');
            route::get('listReturnBooks','school\BorrowController@listReturnBooks');

            //library settings
            route::post('ls_store','school\LibrarySettingsController@store');
            route::get('ls_show','school\LibrarySettingsController@index');
            route::post('ls_update','school\LibrarySettingsController@update');
            route::get('ls_destroy','school\LibrarySettingsController@destroy');
            route::get('ls_select','school\LibrarySettingsController@show');

            //book for fund
            route::post('bff_store','school\BookForFundController@store');
            route::get('bff_show','school\BookForFundController@index');
            route::post('bff_update','school\BookForFundController@update');
            route::get('bff_destroy','school\BookForFundController@destroy');
            route::get('bff_select','school\BookForFundController@show');
            route::get('managefundStock','school\BookForFundController@managefundStock');
            route::post('addfundStock','school\BookForFundController@addfundStock');
            route::get('bookfundProfile','school\BookForFundController@bookfundProfile');

            //give out book for fund
            route::post('gbf_store','school\GiveOutBookFundController@store');
            route::get('gbf_show','school\GiveOutBookFundController@index');
            route::post('gbf_update','school\GiveOutBookFundController@update');
            route::get('gbf_destroy','school\GiveOutBookFundController@destroy');
            route::get('gbf_select','school\GiveOutBookFundController@show');
            route::get('returnBookforfund','school\GiveOutBookFundController@returnBookforfund');
            route::get('listReturnBookforfund','school\GiveOutBookFundController@listReturnBookforfund');
            route::post('addReturnBookforfund','school\GiveOutBookFundController@addReturnBookforfund');

            //stock takings
            route::post('st_store','school\StockTakingsController@store');
            route::get('st_show','school\StockTakingsController@index');
            route::post('st_update','school\StockTakingsController@update');
            route::get('st_destroy','school\StockTakingsController@destroy');
            route::get('st_select','school\StockTakingsController@show');

            //item category
            route::post('ic_store','school\ItemCategoryController@store');
            route::get('ic_show','school\ItemCategoryController@index');
            route::post('ic_update','school\ItemCategoryController@update');
            route::get('ic_destroy','school\ItemCategoryController@destroy');
            route::get('ic_select','school\ItemCategoryController@show');


            route::get('inventTrend','school\InventoryTrendsController@inventTrend');
            route::get('inventryProfile','school\InventoryTrendsController@inventryProfile');

            //add item
            route::post('adi_store','school\AddItemController@store');
            route::get('adi_show','school\AddItemController@index');
            route::post('adi_update','school\AddItemController@update');
            route::get('adi_destroy','school\AddItemController@destroy');
            route::get('adi_select','school\AddItemController@show');

            //item stock
            route::post('is_store','school\ItemStockController@store');
            route::get('is_show','school\ItemStockController@index');
            route::post('is_update','school\ItemStockController@update');
            route::get('is_destroy','school\ItemStockController@destroy');
            route::get('is_select','school\ItemStockController@show');

            //give items
            route::post('gi_store','school\GiveItemsController@store');
            route::get('gi_show','school\GiveItemsController@index');
            route::post('gi_update','school\GiveItemsController@update');
            route::get('gi_destroy','school\GiveItemsController@destroy');
            route::get('gi_select','school\GiveItemsController@show');

            //term
            route::post('tm_store','school\TermsController@store');
            route::get('tm_show','school\TermsController@index');
            route::post('tm_update','school\TermsController@update');
            route::get('tm_destroy','school\TermsController@destroy');
            route::get('tm_select','school\TermsController@show');

            // subject
            route::post('sub_store','school\SubjectController@store');
            route::get('sub_show','school\SubjectController@index');
            route::post('sub_update','school\SubjectController@update');
            route::get('sub_destroy','school\SubjectController@destroy');
            route::get('sub_select','school\SubjectController@show');
            route::get('listteacher','school\SubjectController@listteacher');
            route::post('addSubToTeacher','school\SubjectController@addSubToTeacher');


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

            // student class
            route::post('studentMovement','school\StudentClassController@studentMovement');
            route::get('student_class','school\StudentClassController@listStudent');



            // student certicate
            route::post('stc_store','school\StudntCertificateController@store');
            route::get('stc_show','school\StudntCertificateController@index');
            route::post('stc_update','school\StudntCertificateController@update');
            route::get('stc_destroy','school\StudntCertificateController@destroy');
            route::get('stc_select','school\StudntCertificateController@show');

            //assignment
            route::post('assg_store','school\AssignmentController@store');
            route::get('assg_show','school\AssignmentController@index');
            route::post('assg_update','school\AssignmentController@update');
            route::get('assg_destroy','school\AssignmentController@destroy');
            route::get('assg_select','school\AssignmentController@show');
            route::get('assignmentview','school\AssignmentController@assignmentview');

            //grading system
            route::post('gs_store','school\GradingSystemController@store');
            route::get('gs_show','school\GradingSystemController@index');
            route::post('gs_update','school\GradingSystemController@update');
            route::get('gs_destroy','school\GradingSystemController@destroy');
            route::get('gs_select','school\GradingSystemController@show');


            //grade
            route::post('gd_store','school\GradeController@store');
            route::get('gd_show','school\GradeController@index');
            route::post('gd_update','school\GradeController@update');
            route::get('gd_destroy','school\GradeController@destroy');
            route::get('gd_select','school\GradeController@show');

            //grading
            route::post('gdg_store','school\GradingsController@store');
            route::get('gdg_show','school\GradingsController@index');
            route::post('gdg_update','school\GradingsController@update');
            route::get('gdg_destroy','school\GradingsController@destroy');
            route::get('gdg_select','school\GradingsController@show');
            route::get('gradeAndRemark','school\GradingsController@gradeAndRemark');
            route::get('gradeView','school\GradingsController@gradeView');
            route::post('viewEdit','school\GradingsController@viewEdit');
            route::get('moveTOTrash','school\GradingsController@moveTOTrash');

            //certificate type
            route::post('ct_store','school\CertificateTypeController@store');
            route::get('ct_show','school\CertificateTypeController@index');
            route::post('ct_update','school\CertificateTypeController@update');
            route::get('ct_destroy','school\CertificateTypeController@destroy');
            route::get('ct_select','school\CertificateTypeController@show');

            //certificate
            route::post('cft_store','school\CertificateSubjectController@store');
            route::get('cft_show','school\CertificateSubjectController@index');
            route::post('cft_update','school\CertificateSubjectController@update');
            route::get('cft_destroy','school\CertificateSubjectController@destroy');
            route::get('cft_select','school\CertificateSubjectController@show');

            //add event
            route::post('ae_store','school\AddEventController@store');
            route::get('ae_show','school\AddEventController@index');
            route::post('ae_update','school\AddEventController@update');
            route::get('ae_destroy','school\AddEventController@destroy');
            route::get('ae_select','school\AddEventController@show');

            //other event
            route::post('oe_store','school\OtherEventController@store');
            route::get('oe_show','school\OtherEventController@index');
            route::post('oe_update','school\OtherEventController@update');
            route::get('oe_destroy','school\OtherEventController@destroy');
            route::get('oe_select','school\OtherEventController@show');

            //event announcement
            route::post('ea_store','school\EventAnnouncementController@store');
            route::get('ea_show','school\EventAnnouncementController@index');
            route::post('ea_update','school\EventAnnouncementController@update');
            route::get('ea_destroy','school\EventAnnouncementController@destroy');
            route::get('ea_select','school\EventAnnouncementController@show');
            route::get('eventCalenderview','school\EventAnnouncementController@eventCalenderview');

            //fee structure
            route::post('fs_store','school\FeeStructureController@store');
            route::get('fs_show','school\FeeStructureController@index');
            route::post('fs_update','school\FeeStructureController@update');
            route::get('fs_destroy','school\FeeStructureController@destroy');
            route::get('fs_select','school\FeeStructureController@show');

            //fee type
            route::post('ft_store','school\FeeTypeController@store');
            route::get('ft_select','school\FeeTypeController@show');
            route::get('ft_show','school\FeeTypeController@index');
            route::post('ft_update','school\FeeTypeController@update');
            route::get('ft_destroy','school\FeeTypeController@destroy');

            //fee extras
            route::post('fe_store','school\FeeExtrasController@store');
            route::get('fe_show','school\FeeExtrasController@index');
            route::post('fe_update','school\FeeExtrasController@update');
            route::get('fe_destroy','school\FeeExtrasController@destroy');
            route::get('fe_select','school\FeeExtrasController@show');

            //do invoice
            route::post('di_store','school\DoInvoiceController@store');
            route::get('di_show','school\DoInvoiceController@index');
            route::post('di_update','school\DoInvoiceController@update');
            route::get('di_destroy','school\DoInvoiceController@destroy');
            route::get('di_select','school\DoInvoiceController@show');

            //year
            route::post('yr_store','school\YearController@store');
            route::get('yr_show','school\YearController@index');
            route::post('yr_update','school\YearController@update');
            route::get('yr_destroy','school\YearController@destroy');
            route::get('yr_select','school\YearController@show');

            //email template
            route::post('et_store','school\EmailTemplateController@store');
            route::get('et_show','school\EmailTemplateController@index');
            route::post('et_update','school\EmailTemplateController@update');
            route::get('et_destroy','school\EmailTemplateController@destroy');
            route::get('et_select','school\EmailTemplateController@show');
            route::get('emailInbox','school\EmailTemplateController@emailInbox');


            //notice board
            route::post('nb_store','school\NoticeBoardController@store');
            route::get('nb_show','school\NoticeBoardController@index');
            route::post('nb_update','school\NoticeBoardController@update');
            route::get('nb_destroy','school\NoticeBoardController@destroy');
            route::get('nb_select','school\NoticeBoardController@show');

            //rules regulations
            route::post('rr_store','school\RulesRegulationsController@store');
            route::get('rr_show','school\RulesRegulationsController@index');
            route::post('rr_update','school\RulesRegulationsController@update');
            route::get('rr_destroy','school\RulesRegulationsController@destroy');
            route::get('rr_select','school\RulesRegulationsController@show');

            //address book category
            route::post('addressBookcategory_store','school\AddressBookCategoryController@store');
            route::get('addressBookcategory_show','school\AddressBookCategoryController@index');
            route::post('addressBookcategory_update','school\AddressBookCategoryController@update');
            route::get('addressBookcategory_destroy','school\AddressBookCategoryController@destroy');
            route::get('addressBookcategory_select','school\AddressBookCategoryController@show');
            route::post('storeAddress','school\AddressBookCategoryController@storeAddress');

            //address book 
            route::post('addressBook_store','school\AddressBookController@store');
            route::get('addressBook_show','school\AddressBookController@index');
            route::post('addressBook_update','school\AddressBookController@update');
            route::get('addressBook_destroy','school\AddressBookController@destroy');
            route::get('addressBook_select','school\AddressBookController@show');

            //items manager contact 
            route::post('itemsManagerContact_store','school\ItemsManagerContactController@store');
            route::get('itemsManagerContact_show','school\ItemsManagerContactController@index');
            route::post('itemsManagerContact_update','school\ItemsManagerContactController@update');
            route::get('itemsManagerContact_destroy','school\ItemsManagerContactController@destroy');
            route::get('itemsManagerContact_select','school\ItemsManagerContactController@show');
            route::get('customers','school\ItemsManagerContactController@customers');
            route::get('supplier','school\ItemsManagerContactController@supplier');
            route::get('others','school\ItemsManagerContactController@others');

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

            //email
            route::post('email_store','school\EmailController@store');
            route::get('email_show','school\EmailController@index');
            route::post('email_update','school\EmailController@update');
            route::post('email_destroy','school\EmailController@destroy');
            route::get('email_select','school\EmailController@show');
            route::get('trash','school\EmailController@trash');

            //fee waivers
            route::post('feeWaivers_store','school\FeeWaiversController@store');
            route::get('feeWaivers_show','school\FeeWaiversController@index');
            route::post('feeWaivers_update','school\FeeWaiversController@update');
            route::get('feeWaivers_destroy','school\FeeWaiversController@destroy');
            route::get('feeWaivers_select','school\FeeWaiversController@show');

            //fee arrears
            route::post('feeArrears_store','school\FeeArrearsController@store');
            route::get('feeArrears_show','school\FeeArrearsController@index');
            route::post('feeArrears_update','school\FeeArrearsController@update');
            route::get('feeArrears_destroy','school\FeeArrearsController@destroy');
            route::get('feeArrears_select','school\FeeArrearsController@show');

            //fee pledge
            route::post('feePledge_store','school\FeePledgeController@store');
            route::get('feepledge_show','school\FeePledgeController@index');
            route::post('feepledge_update','school\FeePledgeController@update');
            route::get('feepledge_destroy','school\FeePledgeController@destroy');
            route::get('feepledge_select','school\FeePledgeController@show');

            //fee charge
            route::post('feecharge_store','school\FeeChargeController@store');
            route::get('feecharge_show','school\FeeChargeController@index');
            route::post('feecharge_update','school\FeeChargeController@update');
            route::get('feecharge_destroy','school\FeeChargeController@destroy');
            route::get('feecharge_select','school\FeeChargeController@show');

            //bank_account
            route::post('bankAcc_store','school\BankAccountController@store');
            route::get('bankAcc_show','school\BankAccountController@index');
            route::post('bankAcc_update','school\BankAccountController@update');
            route::get('bankAcc_destroy','school\BankAccountController@destroy');
            route::get('bankAc_select','school\BankAccountController@show');

            //petty cash
            route::post('pettyCash_store','school\PettyCashController@store');
            route::get('pettyCash_show','school\PettyCashController@index');
            route::post('pettyCash_update','school\PettyCashController@update');
            route::get('pettyCash_destroy','school\PettyCashController@destroy');
            route::get('pettyCash_select','school\PettyCashController@show');

            //grants
            route::post('grants_store','school\GrantsController@store');
            route::get('grants_show','school\GrantsController@index');
            route::post('grants_update','school\GrantsController@update');
            route::get('grants_destroy','school\GrantsController@destroy');
            route::get('grants_select','school\GrantsController@show');

            //allowances
            route::post('allowances_store','school\AllowancesController@store');
            route::get('allowances_show','school\AllowancesController@index');
            route::post('allowances_update','school\AllowancesController@update');
            route::get('allowances_destroy','school\AllowancesController@destroy');
            route::get('allowances_select','school\AllowancesController@show');

            //add employee to salary
            route::post('addEmpSal_store','school\AddEmpSalController@store');
            route::get('addEmpSal_show','school\AddEmpSalController@index');
            route::post('addEmpSal_update','school\AddEmpSalController@update');
            route::get('addEmpSal_destroy','school\AddEmpSalController@destroy');
            route::get('addEmpSal_select','school\AddEmpSalController@show');
            route::get('addEmpSal_select','school\AddEmpSalController@show');
            route::get('payrollConfig','school\AddEmpSalController@payrollConfig');
            route::post('payrollConfigedit','school\AddEmpSalController@payrollConfigedit');
            route::get('payrollConfigdelete','school\AddEmpSalController@payrollConfigdelete');
            route::get('payrollConfigselect','school\AddEmpSalController@payrollConfigselect');

            //deduction
            route::post('deduction_store','school\DeductionController@store');
            route::get('deduction_show','school\DeductionController@index');
            route::post('deduction_update','school\DeductionController@update');
            route::get('deduction_destroy','school\DeductionController@destroy');
            route::get('deduction_select','school\DeductionController@show');

            //process_salary
            route::post('processSalary_store','school\ProcessSalaryController@store');
            route::get('processSalary_show','school\ProcessSalaryController@index');
            route::post('processSalary_update','school\ProcessSalaryController@update');
            route::get('processSalary_destroy','school\ProcessSalaryController@destroy');
            route::get('processSalary_select','school\ProcessSalaryController@show');

            //advance_salary
            route::post('advanceSalary_store','school\AdvanceSalaryController@store');
            route::get('advanceSalary_show','school\AdvanceSalaryController@index');
            route::post('advanceSalary_update','school\AdvanceSalaryController@update');
            route::get('advanceSalary_destroy','school\AdvanceSalaryController@destroy');
            route::get('advanceSalary_select','school\AdvanceSalaryController@show');

            //sales item category
            route::post('salesItemCategory_store','school\SalesItemCategoryController@store');
            route::get('salesItemCategory_show','school\SalesItemCategoryController@index');
            route::post('salesItemCategory_update','school\SalesItemCategoryController@update');
            route::get('salesItemCategory_destroy','school\SalesItemCategoryController@destroy');
            route::get('salesItemCategory_select','school\SalesItemCategoryController@show');

            //sales item
            route::post('salesItem_store','school\SalesItemController@store');
            route::get('salesItem_show','school\SalesItemController@index');
            route::post('salesItem_update','school\SalesItemController@update');
            route::get('salesItem_destroy','school\SalesItemController@destroy');
            route::get('salesItem_select','school\EmergencyContactController@show');

            //sales item stock
            route::post('salesItemStock_store','school\SalesItemStockController@store');
            route::get('salesItemStock_show','school\SalesItemStockController@index');
            route::post('salesItemStock_update','school\SalesItemStockController@update');
            route::get('salesItemStock_destroy','school\SalesItemStockController@destroy');
            route::get('salesItemStock_select','school\SalesItemStockController@show');

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

            //fee extrass 
            route::post('storeFeeExtras','school\FeeExtrassController@storeFeeExtras');
            route::get('manageFeeExtras','school\FeeExtrassController@manageFeeExtras');
            route::get('allInvoices','school\FeeExtrassController@allInvoices');
            route::get('generateInvoice','school\FeeExtrassController@generateInvoice');
            route::get('viewInvoice','school\FeeExtrassController@viewInvoice');



            //expense item
            route::post('ExpenseItem_store','school\ExpenseItemController@store');
            route::get('ExpenseItem_show','school\ExpenseItemController@index');
            route::post('ExpenseItem_update','school\ExpenseItemController@update');
            route::get('ExpenseItem_destroy','school\ExpenseItemController@destroy');
            route::get('ExpenseItem_select','school\ExpenseItemController@show');

            //expense category
            route::post('ExpenseCategory_store','school\ExpenseCategoryController@store');
            route::get('ExpenseCategory_show','school\ExpenseCategoryController@index');
            route::post('ExpenseCategory_update','school\ExpenseCategoryController@update');
            route::get('ExpenseCategory_destroy','school\ExpenseCategoryController@destroy');
            route::get('ExpenseCategory_select','school\ExpenseCategoryController@show');

            //expense details
            route::post('ExpenseDetails_store','school\ExpenseDetailsController@store');
            route::get('ExpenseDetails_show','school\ExpenseDetailsController@index');
            route::post('ExpenseDetails_update','school\ExpenseDetailsController@update');
            route::get('ExpenseDetails_destroy','school\ExpenseDetailsController@destroy');
            route::get('ExpenseDetails_select','school\ExpenseDetailsController@show');

            //bank name
            route::post('bankName_store','school\BankNameController@store');
            route::get('bankName_show','school\BankNameController@index');
            route::post('bankName_update','school\BankNameController@update');
            route::get('bankName_destroy','school\BankNameController@destroy');
            route::get('bankName_select','school\BankNameController@show');

            //grant type
            route::post('grantType_store','school\GrantTypeController@store');
            route::get('grantType_show','school\GrantTypeController@index');
            route::post('grantType_update','school\GrantTypeController@update');
            route::get('grantType_destroy','school\GrantTypeController@destroy');
            route::get('grantType_select','school\GrantTypeController@show');

            //payment method
            route::post('PaymentMethod_store','school\PaymentMethodController@store');
            route::get('PaymentMethod_show','school\PaymentMethodController@index');
            route::post('PaymentMethod_update','school\PaymentMethodController@update');
            route::get('PaymentMethod_destroy','school\PaymentMethodController@destroy');
            route::get('PaymentMethod_select','school\PaymentMethodController@show');

            //salary method
            route::post('SalaryMethod_store','school\SalaryMethodController@store');
            route::get('SalaryMethod_show','school\SalaryMethodController@index');
            route::post('SalaryMethod_update','school\SalaryMethodController@update');
            route::get('SalaryMethod_destroy','school\SalaryMethodController@destroy');
            route::get('SalaryMethod_select','school\SalaryMethodController@show');

            //process type
            route::post('ProcessType_store','school\ProcessTypeController@store');
            route::get('ProcessType_show','school\ProcessTypeController@index');
            route::post('ProcessType_update','school\ProcessTypeController@update');
            route::get('ProcessType_destroy','school\ProcessTypeController@destroy');
            route::get('ProcessType_select','school\ProcessTypeController@show');

            //enquiries option
            route::post('EnquiriesOption_store','school\EnquiriesOptionController@store');
            route::get('EnquiriesOption_show','school\EnquiriesOptionController@index');
            route::post('EnquiriesOption_update','school\EnquiriesOptionController@update');
            route::get('EnquiriesOption_destroy','school\EnquiriesOptionController@destroy');
            route::get('EnquiriesOption_select','school\EnquiriesOptionController@show');

            //enquiries
            route::post('Enquiries_store','school\EnquiriesController@store');
            route::get('Enquiries_show','school\EnquiriesController@index');
            route::post('Enquiries_update','school\EnquiriesController@update');
            route::get('Enquiries_destroy','school\EnquiriesController@destroy');
            route::get('Enquiries_select','school\EnquiriesController@show');

            //leaving certificate
            route::post('LeavingCertificate_store','school\LeavingCertificateController@store');
            route::get('LeavingCertificate_show','school\LeavingCertificateController@index');
            route::post('LeavingCertificate_update','school\LeavingCertificateController@update');
            route::get('LeavingCertificate_destroy','school\LeavingCertificateController@destroy');
            route::get('LeavingCertificate_select','school\LeavingCertificateController@show');
            route::get('profileleaving','school\LeavingCertificateController@profile');


            //placement position
            route::post('Position_store','school\PositionController@store');
            route::get('Position_show','school\PositionController@index');
            route::post('Position_update','school\PositionController@update');
            route::get('Position_destroy','school\PositionController@destroy');
            route::get('Position_select','school\PositionController@show');

            //settings position
            route::post('setPosition_store','school\SettingsPositionsController@store');
            route::get('setPosition_show','school\SettingsPositionsController@index');
            route::post('setPosition_update','school\SettingsPositionsController@update');
            route::get('setPosition_destroy','school\SettingsPositionsController@destroy');
            route::get('setPosition_select','school\SettingsPositionsController@show');

            //new placement
            route::post('NewPlacement_store','school\NewPlacementController@store');
            route::get('NewPlacement_show','school\NewPlacementController@index');
            route::post('NewPlacement_update','school\NewPlacementController@update');
            route::get('NewPlacement_destroy','school\NewPlacementController@destroy');
            route::get('NewPlacement_select','school\NewPlacementController@show');
            route::get('profileplacement','school\NewPlacementController@profile');

            //add activities
            route::post('AddActivities_store','school\AddActivitiesController@store');
            route::get('AddActivities_show','school\AddActivitiesController@index');
            route::post('AddActivities_update','school\AddActivitiesController@update');
            route::get('AddActivities_destroy','school\AddActivitiesController@destroy');
            route::get('AddActivities_select','school\AddActivitiesController@show');

            //hostel
            route::post('hostel_store','school\HostelController@store');
            route::get('hostel_show','school\HostelController@index');
            route::post('hostel_update','school\HostelController@update');
            route::get('hostel_destroy','school\HostelController@destroy');
            route::get('hostel_select','school\HostelController@show');

            //hostelRooms
            route::post('hostelRooms_store','school\HostelRoomsController@store');
            route::get('hostelRooms_show','school\HostelRoomsController@index');
            route::post('hostelRooms_update','school\HostelRoomsController@update');
            route::get('hostelRooms_destroy','school\HostelRoomsController@destroy');
            route::get('hostelRooms_select','school\HostelRoomsController@show');

            //hostelbed
            route::post('hostelbed_store','school\HostelBedController@store');
            route::get('hostelbed_show','school\HostelBedController@index');
            route::post('hostelbed_update','school\HostelBedController@update');
            route::get('hostelbed_destroy','school\HostelBedController@destroy');
            route::get('hostelbed_select','school\HostelBedController@show');

            //assignBed
            route::post('assignBed_store','school\AssignBedController@store');
            route::get('assignBed_show','school\AssignBedController@index');
            route::post('assignBed_update','school\AssignBedController@update');
            route::get('assignBed_destroy','school\AssignBedController@destroy');
            route::get('assignBed_select','school\AssignBedController@show');

            //addRoute
            route::post('addRoute_store','school\AddRouteController@store');
            route::get('addRoute_show','school\AddRouteController@index');
            route::post('addRoute_update','school\AddRouteController@update');
            route::get('addRoute_destroy','school\AddRouteController@destroy');
            route::get('addRoute_select','school\AddRouteController@show');

            //Regitration
            route::post('Regitration_store','school\RegistrationDetailsController@store');
            route::get('Regitration_show','school\RegistrationDetailsController@index');
            route::post('Regitration_update','school\RegistrationDetailsController@update');
            route::get('Regitration_destroy','school\RegistrationDetailsController@destroy');
            route::get('Regitration_select','school\RegistrationDetailsController@show');

            //ownership
            route::post('ownership_store','school\OwnershipController@store');
            route::get('ownership_show','school\OwnershipController@index');
            route::post('ownership_update','school\OwnershipController@update');
            route::get('ownership_destroy','school\OwnershipController@destroy');
            route::get('ownership_select','school\OwnershipController@show');

            //institution docs
            route::post('institutnDocs_store','school\InstitutionDocsController@store');
            route::get('institutnDocs_show','school\InstitutionDocsController@index');
            route::post('institutnDocs_update','school\InstitutionDocsController@update');
            route::get('institutnDocs_destroy','school\InstitutionDocsController@destroy');
            route::get('institutnDocs_select','school\InstitutionDocsController@show');

            //contact person
            route::post('contactPerson_store','school\ContactPersonController@store');
            route::get('contactPerson_show','school\ContactPersonController@index');
            route::post('contactPerson_update','school\ContactPersonController@update');
            route::get('contactPerson_destroy','school\ContactPersonController@destroy');
            route::get('contactPerson_select','school\ContactPersonController@show');

            //payment options
            route::post('paymentOptions_store','school\PaymentOptionsController@store');
            route::get('paymentOptions_show','school\PaymentOptionsController@index');
            route::post('paymentOptions_update','school\PaymentOptionsController@update');
            route::get('paymentOptions_destroy','school\PaymentOptionsController@destroy');
            route::get('paymentOptions_select','school\PaymentOptionsController@show');

            //class room
            route::post('classRoom_store','school\ClassRoomsController@store');
            route::get('classRoom_show','school\ClassRoomsController@index');
            route::post('classRoom_update','school\ClassRoomsController@update');
            route::get('classRoom_destroy','school\ClassRoomsController@destroy');
            route::get('classRoom_select','school\ClassRoomsController@show');

            //clearanceDep
            route::post('clearanceDep_store','school\ClearanceDepartmentsController@store');
            route::get('clearanceDep_show','school\ClearanceDepartmentsController@index');
            route::post('clearanceDep_update','school\ClearanceDepartmentsController@update');
            route::get('clearanceDep_destroy','school\ClearanceDepartmentsController@destroy');
            route::get('clearanceDep_select','school\ClearanceDepartmentsController@show');

            //counties
            route::post('counties_store','school\CountiesController@store');
            route::get('counties_show','school\CountiesController@index');
            route::post('counties_update','school\CountiesController@update');
            route::get('counties_destroy','school\CountiesController@destroy');
            route::get('counties_select','school\CountiesController@show');

            //sub counties
            route::post('subcounties_store','school\SubCountyController@store');
            route::get('subcounties_show','school\SubCountyController@index');
            route::post('subcounties_update','school\SubCountyController@update');
            route::get('subcounties_destroy','school\SubCountyController@destroy');
            route::get('subcounties_select','school\SubCountyController@show');

            //medical records
            route::post('medicalRecords_store','school\MedicalRecordsController@store');
            route::get('medicalRecords_show','school\MedicalRecordsController@index');
            route::post('medicalRecords_update','school\MedicalRecordsController@update');
            route::get('medicalRecords_destroy','school\MedicalRecordsController@destroy');
            route::get('medicalRecords_select','school\MedicalRecordsController@show');

            //record sales
            route::post('recordSales_store','school\RecordSalesController@store');
            route::get('recordSales_show','school\RecordSalesController@index');
            route::post('recordSales_update','school\RecordSalesController@update');
            route::get('recordSales_destroy','school\RecordSalesController@destroy');
            route::get('recordSales_select','school\RecordSalesController@show');

            //student timetable
            route::post('studentTimetable_store','school\StudentTimetableController@store');
            route::get('studentTimetable_show','school\StudentTimetableController@index');
            route::post('studentTimetable_update','school\StudentTimetableController@update');
            route::get('studentTimetable_destroy','school\StudentTimetableController@destroy');
            route::get('studentTimetable_select','school\StudentTimetableController@show');

            //teacher timetable
            route::post('teacherTimetable_store','school\TeacherTimetableController@store');
            route::get('teacherTimetable_show','school\TeacherTimetableController@index');
            route::post('teacherTimetable_update','school\TeacherTimetableController@update');
            route::get('teacherTimetable_destroy','school\TeacherTimetableController@destroy');
            route::get('teacherTimetable_select','school\TeacherTimetableController@show');


            //employee attendance
            route::post('employeAtten_store','school\EmployeeAttendanceController@store');
            route::get('employeAtten_show','school\EmployeeAttendanceController@index');
            route::post('employeAtten_update','school\EmployeeAttendanceController@update');
            route::get('employeAtten_destroy','school\EmployeeAttendanceController@destroy');
            route::get('employeAtten_select','school\EmployeeAttendanceController@show');
            route::get('selectEmployee','school\EmployeeAttendanceController@selectEmployee');


            //student attendance
            route::post('studentAtten_store','school\ClassAttendanceController@store');
            route::get('studentAtten_show','school\ClassAttendanceController@index');
            route::post('studentAtten_update','school\ClassAttendanceController@update');
            route::get('studentAtten_destroy','school\ClassAttendanceController@destroy');
            route::get('studentAtten_select','school\ClassAttendanceController@show');
            route::get('classAttendanceView','school\ClassAttendanceController@classAttendanceView');


            //discipline
            route::post('discipline_store','school\DisciplineController@store');
            route::get('discipline_show','school\DisciplineController@index');
            route::post('discipline_update','school\DisciplineController@update');
            route::get('discipline_destroy','school\DisciplineController@destroy');
            route::get('discipline_select','school\DisciplineController@show');
            route::post('discipline_action','school\DisciplineController@action');
            route::get('discipline_profile','school\DisciplineController@profile');

            //requisitions
            route::post('requisitions_store','school\RequisitionsController@store');
            route::get('requisitions_show','school\RequisitionsController@index');
            route::post('requisitions_update','school\RequisitionsController@update');
            route::get('requisitions_destroy','school\RequisitionsController@destroy');
            route::get('requisitions_select','school\RequisitionsController@show');
            route::get('requisitions_action','school\RequisitionsController@action');

            ///syllabus
            route::post('makeSyllabus','school\SyllabusController@makeSyllabus');
            route::get('syllabusselect','school\SyllabusController@select');
            //route::post('requisitions_update','school\SyllabusController@update');


            //institution details
            route::post('insdetails_store','school\InstitutionDetailsController@store');
            route::get('insdetailss_show','school\InstitutionDetailsController@index');
            route::post('insdetails_update','school\InstitutionDetailsController@update');
            route::get('insdetails_destroy','school\InstitutionDetailsController@destroy');
            route::get('insdetailss_select','school\InstitutionDetailsController@show');
            route::get('ins_details','school\InstitutionDetailsController@institutn');
            route::post('institution_upload','school\InstitutionDetailsController@insdocs');
            route::get('institution_upload_select','school\InstitutionDetailsController@docSelect');

            //contact details
            route::post('contactdetails_store','school\InstitutionSetupController@store');
            route::get('contactdetails_show','school\InstitutionSetupController@index');
            route::post('contactdetails_update','school\InstitutionSetupController@update');
            route::get('contactdetails_destroy','school\InstitutionSetupController@destroy');
            route::get('contactdetails_select','school\InstitutionSetupController@show');

            //reports
            route::get('StudentHistoryreport','school\InstitutionSetupController@StudentHistoryreport');
            route::get('AdmissionReport','school\InstitutionSetupController@AdmissionReport');
            route::get('ActivitiesReport','school\InstitutionSetupController@ActivitiesReport');
            route::get('feePaymentSummery','school\InstitutionSetupController@feePaymentSummery');
            route::get('feeStatus','school\InstitutionSetupController@feeStatus');
            route::get('feeArrears','school\InstitutionSetupController@feeArrears');
            route::get('feeExtrassReport','school\InstitutionSetupController@feeExtrassReport');
            route::get('feeExtrassList','school\InstitutionSetupController@feeExtrassList');
            route::get('feePaymentReport','school\InstitutionSetupController@feePaymentReport');
            route::get('ExamReport','school\InstitutionSetupController@ExamReport');
            route::get('jointExamReport','school\InstitutionSetupController@jointExamReport');
            route::get('smsExamsReport','school\InstitutionSetupController@smsExamsReport');
            route::get('GradeAnalysis','school\InstitutionSetupController@GradeAnalysis');
            route::get('expenseSummeryReport','school\InstitutionSetupController@expenseSummeryReport');
            route::get('DetailExpensesReport','school\InstitutionSetupController@DetailExpensesReport');
            route::get('wagsReport','school\InstitutionSetupController@wagsReport');
            route::get('SchoolAssets','school\InstitutionSetupController@SchoolAssets');
            route::get('BookFundReport','school\InstitutionSetupController@BookFundReport');

            //admin
            route::get('admindata','school\AdminController@Admindata');
            route::post('changePassword','school\AdminController@changePassword');
            route::post('adminupdate','school\AdminController@adminupdate');


            });

            //online registration
            route::post('onlineRegistration_store','school\OnlineRegistrationController@store');
            route::get('onlineRegistration_show','school\OnlineRegistrationController@show');
            route::post('onlineRegistration_update','school\OnlineRegistrationController@update');
            route::get('onlineRegistration_destroy','school\OnlineRegistrationController@destroy');
            route::get('onlineRegistration_list','school\OnlineRegistrationController@index');

            Route::get('testCode', function () {
                dd(config('jetstream.middleware', ['web']));
            });
