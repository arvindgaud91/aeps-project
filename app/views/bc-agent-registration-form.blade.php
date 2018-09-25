<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    <style>
    @font-face {
      font-family: 'Elegance';
      font-size: '5px';
      font-weight: normal;
      font-style: normal;
      font-variant: normal;
      src: url("http://eclecticgeek.com/dompdf/fonts/Elegance.ttf") format("truetype");
    }
    body {
      font-family: Elegance, sans-serif;
    }
    td{font-size:12px;}
    </style>
</head>
<body>
  <div><img height="70px" src="images/logo1.png"></div>
  <h4 style="text-align:center;font-size:14px;text-decoration: underline;margin:5px 0;padding:0;" >ONBOARDING OF CUSTOMER SERVICE POINT (CSP)</h4>
  <table width="100%">
    <tr>
      <td width="80%">
        <table width="100%">
          <tr>
            <td width="5%"><div>CSP Code</div></td>
            <td width="15%">
              <table width="100%">
                <tr>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                </tr>
              </table>
            </td>
            <td width="7%"><div>Service Required:</div></td>
            <td width="2%"><div>DMT</div></td>
            <td width="2%">
              <table width="100%">
                <tr>
                  <td><img height="13px" style="margin-top:3px;" src="<?php echo $services_dmt;?>"></td>
                </tr>
              </table>
            </td>
            <td width="2%"><div>AEPS</div></td>
            <td width="2%">
              <table width="100%">
                <tr>
                  <td><img height="13px" style="margin-top:3px;" src="<?php echo $services_aeps;?>"></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <br/>
        <table width="100%">
          <tr>
            <td width="39%"><div>Device Information (for AEPS) IMEI No:</div></td>
            <td width="41%">
              <table width="100%" >
                <tr>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                </tr>
              </table>
            </td>
            <td width="2%"><div>Type: </div></td>
            <td width="16%">
              <table width="100%">
                <tr>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                  <td style="width:30px;height:15px;border:1px solid #000;"></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <h4>APPLICANT/CSP INFORMATION</h4>  
        <table> 
          <tr>
            <td width="30%">1. Name of Applicant/ CSP:</td>
            <td>&nbsp;<u><?php echo ucfirst($name)?></u></td>
          </tr>
        </table>
      </td>
      <td width="20%">
        <div style="margin-left: 0px;"><img src="<?php echo $profilepicurl; ?>" width:"140" height="140"></div>
      </td>
    </tr>
  </table>
  <table cellpadding="4" width="100%">
    <tr>
      <td width="10%">2. Gender:</td>
      <td width="15%"><img height="13px" style="margin-top:3px;" src="images/check.png">&nbsp;&nbsp;<u><?php echo ucfirst($gender)?></u></td>
      <td width="5%">Education:</td>
      <td width="20%"><u><?php echo ucfirst($education)?></u></td>
      <td width="2%"></td>
      <td width="10%">DOB:-</td>
      <td width="20%" align="left"><u><?php echo $dob?></u></td>
    </tr>
  </table>
    <table cellpadding="4" width="80%">
      <tr>
        <td width="32%">3. Name of the Establishment:</td>
        <td>&nbsp;<u><?php echo $establishment?></u></td>
      </tr>
    </table>
    <table cellpadding="4" width="80%">
      <tr>
        <td width="20%">4. Outlet Address: </td>
        <td width="80%"><u><?php echo $address?></u></td>
      </tr>
    </table>
    <table cellpadding="4" width="100%">
      <tr>
        <td width="8%">&nbsp;&nbsp;&nbsp;&nbsp;City: </td>
        <td width="10%"><u><?php echo $city;?></u></td>
        <td width="7%">State: </td>
        <td width="15%"><u><?php echo $state;?></u></td>
        <td width="9%">Country: </td>
        <td width="10%"><u><?php echo $country;?></u></td>
        <td width="7%">Pin: </td>
        <td width="10%"><u><?php echo $pin;?></u></td>
        <td width="8%">Tel no: </td>
        <td width="20%"><u><?php echo $telephone;?></u></td>
      </tr>
    </table>
    <table width="100%">
      <tr>
        <td width="15%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mobile number: </td>
        <td width="20%"><u><?php echo $mobile;?></u></td>
        <td width="9%">Email: </td>
        <td width="40%"><u><?php echo $email;?></u></td>
      </tr>
    </table>
    <table cellpadding="4" width="80%">
      <tr>
        <td width="25%">5. Residential Address: </td>
        <td width="75%"><u><?php echo $laddress;?></u></td>
      </tr>
    </table>
    <table cellpadding="4" width="100%">
      <tr>
        <td width="20%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Landmark: </td>
        <td width="23%"><u></u></td> 
        <td width="15%">City: </td>
        <td width="20%"><u><?php echo $lcity;?></u></td>
        <td width="10%">State: </td>
        <td width="20%"><u><?php echo $lstate;?></u></td>
      </tr>
    </table>
    <table width="100%">
      <tr>
        <td width="10%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Country: </td>
        <td width="10%"><u><?php echo $country;?></u></td>
        <td width="5%">PIN: </td>
        <td width="10%"><u><?php echo $lpincode;?></u></td>
        <td width="7%">Tel no: </td>
        <td width="10%"><u><?php echo $alternate_number;?></u></td>
        <td width="15%">Mobile number: </td>
        <td width="15%"><u><?php echo $mobile;?></u></td>
      </tr>
    </table>
    <!--
    <table cellpadding="4" width="100%">
      <tr>
        <td width="16%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rented/Owned: </td>
        <td width="20%"><u>'.$res_rent.'</ul></td>
        <td>Years in Location: </td>
        <td><u>'.$result['year_in_location'].' Years</u></td>
        <td width="10%">&nbsp;Email: </td>
        <td width="25%"><u>'.$result['email'].'</ul></td>
      </tr>
    </table> 
    -->
    <table cellpadding="4" width="80%">
      <tr>
        <td colspan="2">6.&nbsp;&nbsp;Business information: </td>
      </tr>
      <tr>
        <td width="50%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Description of services at present Outlet:</td>
        <td width="40%"><u></u></td>
        <td width="12%">PAN No.</td>
        <td width="10%"><u><?php echo $pancard;?></u></td>
      </tr>
    </table>
    <table cellpadding="4" width="80%">
      <!--<tr>
        <td width="45%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No of People Working at outlet:</td>
        <td width="42%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>'.$result['no_of_people_working'].'</u></td>
      </tr>
      <tr>
        <td width="30%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nearest Bank name</td>
        <td width="27%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>'.$result['nearest_bank'].'</u></td>
        <td width="45%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Location</td>
        <td width="42%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>'.$result['city'].'</u></td>
      </tr>
      -->
      <tr>
        <td width="25%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Operating Hours:</td>
        <td width="30%"><u><?php echo $operating_hours;?></u></td>
        <td width="20%">Weekly Off:</td>
        <td width="42%"><u><?php echo $weekly_off;?></u></td>
      </tr>
    </table>
    <table cellpadding="4" width="80%">
      <tr>
        <td colspan="2">If you having similar arrangement with any other Bank, Society or BC please relevant details: </td>
      </tr>
      <tr>
        <td colspan="2" width="100%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div style="width:100%;margin-left:0px;border-bottom:1px solid #000;"><u></u></div></td>
      </tr>
    </table>
    <!--<table cellpadding="4" width="80%">
      <tr>
        <td  colspan="2" width="55%">7.&nbsp;&nbsp;PAN No (or form 60 if PAN not available) </td>
      </tr>
    </table>
    -->
    <table cellpadding="4" width="80%">
      <tr>
        <td width="35%">7. Banking Information: </td>
        <td width="7%">Bank:</td>
        <td width="45%"><u><?php echo $bank_name;?></u></td>
        <td width="15%">A/c type:</td>
        <td width="15%"><u><?php echo $account_type;?></u></td>
      </tr>
    </table>
    <table cellpadding="4" width="80%">
      <tr>
        <td width="15%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A/c No:</td>
        <td width="35%"><u><?php echo $account_number;?></u></td>
        <td width="15%">IFSC Code:</td>
        <td width="40%"><u><?php echo $ifsc;?></u></td>
      </tr>
    </table>
    <div width="100%" style="margin-bottom:10px;width:100%;display:block;border-bottom:1px dashed #000;">&nbsp;</div>
    <table><tr><td></td></tr></table>
    <table width="100%">
      <tr>
        <th width="10%"></th>
        <th width="20%"><div style="border:1px solid #000;font-size:14px;line-height:25px;height:30px;">Applicant / CSP</div></th>
        <th width="15%"></th>
        <th width="15%"><div style="border:1px solid #000;font-size:14px;line-height:25px;height:30px;">Declaration</div></th>
        <th width="10%"></th>
        <th width="15%"><div style="border:1px solid #000;font-size:14px;line-height:25px;height:30px;">Partner</div></th>
        <th width="10%"></th>
      </tr>
    </table>
    <table>
      <tr>
        <td width="52%" >
          <br/><br/>
          <div>I <strong><u><?php echo $name;?></u> </strong>hereby declared that all the statement made by me in this application form are true and complete to the best of my knowledge.</div><br/>
          <div style="height:60px;"/></div>
          <div>Applicant/CSP signature</div><br/>
          <div>Name: <strong><u><?php echo $name;?></u> </strong><br/>
          Date: <u><?php echo date("d-m-Y")?></u></div>
          <br/><br/>
          I request you to appoint me to act as a Customer Service Point in the location of <u><?php echo $city;?></u>
        </td>
        <td width="1%" style="border-right:1px dashed #000;"></td>
        <td width="50%" style="padding-left:10px;">
          <div>I've met applicant/CSP and the originals of all documents produced have been seen & verified by me.</div><br/>
          <table>
            <tr>
              <td width="70%">
                <div style="height:60px;"></div>
                <div>Signature of the Partner(with official seal)</div><br/>
                <div>Name: <strong><u style="width:200px;"></u></strong><br/>
                Date: <u><?php echo date("d-m-Y"); ?></u></div>
              </td>
              <td width="30%"><img src="images/dipl-stamp.png"></td>
            </tr>
          </table>
          
          
          <br/><br/>
        </td>
      </tr>
    </table>
    <br/><br/>
    <table>
      <tr><th align="center"><strong>DECLARATIONS</strong></th></tr>
      <tr>
        <td>
          <br/>
          <div>I hereby submit following declarations as an applicant/CSP of <u><?php echo $name;?></u> who is a Business Correspondent of RBL Bank Ltd.</div>
          <div>a. I have not been found/pronounced to be of unsound mind by any competent authority and declared/adjudicated as insolvent by any
          competent court;</div>
          <div>b. I have not been found guilty of any criminal offence by any court of competent jurisdiction;</div>
          <div>c. I have neither been found guilty of any criminal offences in the course of any investigation nor have I participated in or connived at anyfraud, dishonesty or misrepresentation against anyone.</div>
          <div>d. I have not violated the code of conduct of any bank or declared a willful defaulter by any bank or/financial institution. –</div>
          <div>e. I promise not to share the customer details with others and use only for the purpose of canvassing business of The RBL Bank Limited.</div>
          <div>f. The RBL Bank Limited Business Facilitator/Business Correspondent scheme shared by <u><?php echo $name;?></u> has been read by me and I/We
          accept the same as binding upon me.</div>
          <div>g. I hereby declare that all the information provided is true and correct to the best of my knowledge and belief. I understand that my
          application is liable to be rejected if it does not satisfy internal verification of the Bank as per the Bank norms. Notwithstanding anything
          contained in this declaration RBL Bank may in its sole discretion terminate the CASH POINT business from the above location as and
          when RBL Bank deems fit.</div>
          <div>h. I have all the necessary permission and I am legally allowed to do business at the above mentioned address/premises.</div>
          <div>i. I hereby irrevocably and unconditionally undertake to indemnify and keep the RBL Bank indemnified against all or any loss, damage,
          cost, expenses, penalties and charges that may be incurred by and/ or caused to RBL Bank arising out of appointing <u><?php echo $name;?></u> (name of
          CSP) as a Customer Service Point of RBL Bank.</div>
          <br/>
          <div>Yours faithfully, </div>
        </td>
      </tr>
    </table>
    <table width="100%">
      <tr>
        <td width="60%">
          <table><tr><td height="60px;"></td></tr></table>
          <div>Signature of the Applicant<br/>
          Name: <strong><u><?php echo ucfirst($name); ?></u> </strong></div>
        </td>
        <td width="40%">
          <table><tr><td height="60px;"></td></tr></table>
          <div>Date: <u><?php echo date("d-m-Y"); ?></u><br/>
          Place: <u><?php echo $city; ?></u></div>
        </td>
      </tr>
    </table>
    <div width="100%" style="margin-bottom:10px;width:100%;display:block;border-bottom:1px dashed #000;">&nbsp;</div>
    <table><tr><td></td></tr></table>
    <table>
      <tr><th align="center"><strong>FOR PARTNER USE / LOCAL INTELLIGENCE FORM</strong></th></tr>
      <tr>
        <td>
          <br/>
          <div>a. Applicant(s) interviewed for the purpose of approving the applicant(s) to act as Business Facilitator/Business Correspondent
  on</div>
          <div>b. Particulars of identification verified with the originals and copies obtained</div>
          <div>b. I have not been found guilty of any criminal offence by any court of competent jurisdiction;</div>
          <div style="border:1px solid #000;padding:10px;">
            &nbsp;&nbsp;KYC Documents(Submit self-attested documentation proof for one of each of three below)<br/>
            &nbsp;&nbsp;Identity Proof: Passport, Pan Card, Voter ID, Aadhaar Card, Driving License, Others (specify)<br/>
            &nbsp;&nbsp;Address Proof: Passport, Voter ID, Aadhaar Card, Driving License, Latest Bank Statement, Latest Electricity Bill, Others (specify)<br/>
            &nbsp;&nbsp;Shop & Establishment form: Yes / No
          </div>
          <div>c. I/we have met the above CSP in Person and visited the establishment. I/we hereby confirm the Identity of CSP and address of
  establishment mentioned in this form is as per the documents submitted by the CSP. CPS has necessary permission and legally allowed
  to conduct business in premises mentioned above.</div>
          <div>d. I/we have done thorough due diligence of above specified CSP and shall be liable to indemnify RBL Bank and its officials from any
  unforeseen events and consequences arising due to CSP not having valid permission to run its business including but not limited to
  business registration certificate such as ‘Shop and Establishment License’ etc.</div>
          <div>e. CSP is operating this business for last <u>&nbsp;&nbsp;&nbsp;&nbsp;</u> month’s/Years and have been found suitable to conduct the RBL Bank CASH POINT
  Business form the above mentioned location</div>
        </td>
      </tr>
    </table>
    <br/>
    <table>
      <tr>
        <td>
          <div style="height:60px;"></div>
          <div>Signature of the Partner (Official)</div><br/>
          <div>Name: <br/><br/>
          Designation: <br/><br/>
          Employee ID No: </div>
        </td>
      </tr>
    </table>
</body>
</html>