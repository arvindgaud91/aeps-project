<?php
use Acme\Auth\Auth;
use Acme\SMS\SMS;


class BcAgentController extends BaseController
{
    function __construct()
    {
        
    } 

    public function postBcAgentForm()
    { 
        $bcagent = new BCAgentKycDetails;

        $user_id = Auth::user()->id;

        if (BCAgentKycDetails::where('mobile_number', '=', Input::get('mobile_number'))->count() > 0) {
            return Response::json(['message' => 'Mobile Number Already Exists!'], 401);
        }

        if (BCAgentKycDetails::where('email_address', '=', Input::get('email_address'))->count() > 0) {
            return Response::json(['message' => 'Email ID Already Exists!'], 402);
        }

        if (BCAgentKycDetails::where('pancard', '=', Input::get('pancard'))->count() > 0) {
            return Response::json(['message' => 'PAN Number Already Exists!!'], 403);
        }

        $date_time = date("Y-m-d-H-i-s");

        $destinationPath =  public_path(). '/upload/kyc';

        $file2=Input::file('file2');
        $kyc_add_proof = $date_time . '' .$user_id . '_add_proof.' . $file2->getClientOriginalExtension();
        $file2->move($destinationPath . '/', $kyc_add_proof);

        $file3=Input::file('file3');
        $kyc_pan_card = $date_time . '' .$user_id . '_id_proof.' . $file3->getClientOriginalExtension();
        $file3->move($destinationPath . '/', $kyc_pan_card); 

        if(!empty(Input::file('file4'))){
            $file4=Input::file('file4');
            $kyc_profile_pic = $date_time . '' .$user_id . '_photograph.' . $file3->getClientOriginalExtension();
            $file4->move($destinationPath . '/', $kyc_profile_pic);
        }else{
            
            $kyc_profile_pic = '';
        }

        if(Input::get('services_aeps') == 'false'){
            $aeps = '';
        }else{
            $aeps = 'AEPS';
        }

        if(Input::get('services_dmt') == 'false'){
            $dmt = '';
        }else{
            $dmt = 'DMT';
        }

        $status = '0';

        $bcagent->user_id = $user_id;
        $bcagent->bc_agent_name = Input::get('bc_agent_name');
        $bcagent->middle_name = Input::get('middle_name');
        $bcagent->last_name = Input::get('last_name');
        $bcagent->address = Input::get('address');
        $bcagent->area = Input::get('area');
        $bcagent->city=Input::get('city');
        $bcagent->district=Input::get('district');
        $bcagent->state=Input::get('state');
        $bcagent->pincode=Input::get('pincode');
        $bcagent->mobile_number=Input::get('mobile_number');
        $bcagent->laddress=Input::get('laddress');
        $bcagent->larea=Input::get('larea');
        $bcagent->lcity=Input::get('lcity');
        $bcagent->ldistrict=Input::get('ldistrict');
        $bcagent->lstate=Input::get('lstate');
        $bcagent->lpincode=Input::get('lpincode');
        $bcagent->telephone=Input::get('telephone');
        $bcagent->alternate_number=Input::get('alternate_number');
        $bcagent->email_address=Input::get('email_address');
        $bcagent->date_of_birth=Input::get('date_of_birth');
        $bcagent->description_of_outlet=Input::get('description_of_outlet');
        $bcagent->pancard=Input::get('pancard');
        $bcagent->ifsc=Input::get('ifsc');
        $bcagent->account_number=Input::get('account_number');
        $bcagent->services_aeps=$aeps;
        $bcagent->services_dmt=$dmt;
        $bcagent->imei_no=Input::get('imei_no'); 
        $bcagent->establishment=Input::get('establishment');
        $bcagent->education=Input::get('education');
        $bcagent->gender=Input::get('gender');
        $bcagent->operating_hours=Input::get('operating_hours');
        $bcagent->weekly_off=Input::get('weekly_off');
        $bcagent->bank_name=Input::get('bank_name');
        $bcagent->account_type=Input::get('account_type');
        $bcagent->addressproofurl=$kyc_add_proof;
        $bcagent->idproofurl=$kyc_pan_card;
        $bcagent->profilepicurl=$kyc_profile_pic;
        $bcagent->status=$status;
        $bcagent->dig_code='';
        $bcagent->save(); 

        if($bcagent)
        {

            return Response::json(['message' => 'BC Agent Form Submitted Successfully'], 200);
        }else
        {
            return Response::json(['message' => 'BC Agent Form Submitted UnSuccessfully'], 400);
        }
    }

    public function getBcAgentReport(){
        Paginator::setPageName('page');
        if(Auth::user()->isSalesExecutive()){
 
            $bcAgentObj = DB::table('bc_agent_kyc_details')
            ->where('user_id',Auth::user()->id)
            ->paginate(100);
            $bcAgents = $bcAgentObj->getItems();

            return View::make('reports.bc-agents-reports',['bcAgents' => $bcAgents,'bcAgentObj' => $bcAgentObj]);
        }
    }


    // public function generateRegistrationPDF()
    // {
    //     $view = \View::make('bc-agent-registration-form');
    //     $html = $view->render();
        
    //     $pdf = new TCPDF();
    //     $pdf::SetTitle('Hello World'); 
    //     $pdf::AddPage();
    //     $pdf::writeHTML($html, true, false, true, false, '');
    //     $pdf::Output('hello_world.pdf');
    // }

    public function generateRegistrationPDF($id)
    {

        $bcAgentObj = DB::table('bc_agent_kyc_details')
            ->where('id',$id)
            ->paginate(100);
        $bcAgents = $bcAgentObj->getItems();
        // echo '<pre>';
        // print_r($bcAgents['0']);
        // echo '<pre>';
        // die;

        if($bcAgents['0']->gender==0){
            $gender='Male';
        }elseif($bcAgents['0']->gender==1){
            $gender='Female';
        }else{
            $gender='Transgender';
        }

        if($bcAgents['0']->operating_hours==0){
            $operating_hours='8:00 AM to 5:00 PM';
        }elseif($bcAgents['0']->operating_hours==1){
            $operating_hours='9:00 AM to 6:00 PM';
        }elseif($bcAgents['0']->operating_hours==2){
            $operating_hours='10:00 AM to 7:00 PM';
        }else{
            $operating_hours='11:00 AM to 8:00 PM';
        }

        if($bcAgents['0']->weekly_off==0){
            $weekly_off='';
        }elseif($bcAgents['0']->weekly_off==1){
            $weekly_off='SUNDAY';
        }elseif($bcAgents['0']->weekly_off==2){
            $weekly_off='MONDAY';
        }elseif($bcAgents['0']->weekly_off==3){
            $weekly_off='TUESDAY';
        }elseif($bcAgents['0']->weekly_off==4){
            $weekly_off='WEDNESDAY';
        }elseif($bcAgents['0']->weekly_off==5){
            $weekly_off='THURSDAY';
        }elseif($bcAgents['0']->weekly_off==6){
            $weekly_off='FRIDAY';
        }else{
            $weekly_off='SATURDAY';
        }

        if($bcAgents['0']->account_type==0){
            $account_type='Current';
        }else{
            $account_type='Saving';
        }


        if($bcAgents['0']->services_aeps=='AEPS'){
            $services_aeps='images/check.png';
        }else{
            $services_aeps='images/uncheck.png';
        }
        if($bcAgents['0']->services_dmt=='DMT'){
            $services_dmt='images/check.png';
        }else{
            $services_dmt='images/uncheck.png';
        }

        if($bcAgents['0']->profilepicurl==''){
            $profilepicurl='images/applicant-recent-photo.jpg';
        }else{
            $profilepicurl='upload/kyc/'.$bcAgents['0']->profilepicurl;
        }

        $name = $bcAgents['0']->bc_agent_name . ' ' . $bcAgents['0']->middle_name . ' ' . $bcAgents['0']->last_name;
        $country = 'INDIA';

        $parameter = array();
        $parameter['name']              = $name;
        $parameter['gender']            = $gender;
        $parameter['education']         = $bcAgents['0']->education;
        $parameter['dob']               = $bcAgents['0']->date_of_birth;
        $parameter['establishment']     = $bcAgents['0']->establishment;
        $parameter['address']           = $bcAgents['0']->address;
        $parameter['city']              = $bcAgents['0']->city;
        $parameter['state']             = $bcAgents['0']->state;
        $parameter['country']           = $country;
        $parameter['pin']               = $bcAgents['0']->pincode;
        $parameter['telephone']         = $bcAgents['0']->telephone;
        $parameter['mobile']            = $bcAgents['0']->mobile_number;
        $parameter['email']             = $bcAgents['0']->email_address;
        $parameter['laddress']          = $bcAgents['0']->laddress;
        $parameter['larea']             = $bcAgents['0']->larea;
        $parameter['lcity']             = $bcAgents['0']->lcity;
        $parameter['lstate']            = $bcAgents['0']->lstate;
        $parameter['lpincode']          = $bcAgents['0']->lpincode;
        $parameter['alternate_number']  = $bcAgents['0']->alternate_number;
        $parameter['pancard']           = $bcAgents['0']->pancard;
        $parameter['operating_hours']   = $operating_hours;
        $parameter['weekly_off']        = $weekly_off;
        $parameter['bank_name']         = $bcAgents['0']->bank_name;
        $parameter['account_type']      = $account_type;
        $parameter['account_number']    = $bcAgents['0']->account_number;
        $parameter['ifsc']              = $bcAgents['0']->ifsc;
        $parameter['services_aeps']     = $services_aeps;
        $parameter['services_dmt']      = $services_dmt;
        $parameter['profilepicurl']      = $profilepicurl;
 
        $pdf = PDF::loadView('bc-agent-registration-form', $parameter);
        return @$pdf->stream("registration-form.pdf");
    }


    public function getBcAgentRegistrationData($id){
        Paginator::setPageName('page');
        if(Auth::user()->isSalesExecutive()){
 
            $bcAgentObj = DB::table('bc_agent_kyc_details')
            ->where('id', $id)
            ->paginate(100);
            $bcAgents = $bcAgentObj->getItems();

            return View::make('bc-agent-update-registration-form',['bcAgents' => $bcAgents,'bcAgentObj' => $bcAgentObj]);
        }
    }


    public function postBcagentRegistrationPDF ($id) 
    { 
        //if (! Auth::user()) return Response::json('Unauthorized', 401);
 
        $user_id = Auth::user()->id;

        $date_time = date("Y-m-d-H-i-s");

        $destinationPath =  public_path(). '/upload/registration-form';
        $file1=Input::file('file1');
        $registration_pdf_form = $date_time . '' .$user_id . '_kyc_form.' . $file1->getClientOriginalExtension();
        $file1->move($destinationPath . '/', $registration_pdf_form);

        $vendorSubmitted = ['registration_pdf_url'=>$registration_pdf_form, 'status'=>'1'];//Input::only('registration_pdf_url');
        $vendor = BCAgentKycDetails::where('id', $id)->update($vendorSubmitted);
        return Response::json('success', 200);
    } 


}