<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')

<title>Digital India Payments Certificate</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style> 
input[type=text] {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    box-sizing: border-box;
    border: none;
    border-bottom: 1px solid black;
    background-color:fef3f0;
    width:33%;
    font-size:20;
}

</style>
<style type="text/css" media="print">
  @page{
    size: auto;
    margin: 0mm;
  }

  .be-left-sidebar,.no-print{
    display: none;
  }

  @media print{ 
    #content{ 
      width:100%;
      height: 100%;
      top: 0; 
    }
    img {
      height: 80%;
    }
  }
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<!-- ImageReady Slices (Untitled-2) -->
<!-- <div id="editor"></div>
<button id="btn">Generate PDF</button> -->
<center>
  <i style="float: right; cursor: pointer;font-size: x-large;" class="icon mdi mdi-print no-print" onclick="window.print();"></i>
  <!-- <i style="float: right; cursor: pointer;" class="icon mdi mdi-print" onclick="window.print();"></i> -->
</center>

<table style="background-color: #fef3f0;" id="content" width="1024" height="787" border="0" cellpadding="0" cellspacing="0">
  <tr class="blank" bgcolor="#00FF00">
    <td colspan="9">
      <img src="{{ asset('images/Certificate_01.gif')}}" width="1024" height="43" alt=""></td>
  </tr>
  <tr class="blank">
    <td colspan="9">
      <img src="{{ asset('images/Certificate_01.gif')}}" width="1024" height="43" alt=""></td>
  </tr>
  <tr>
    <td rowspan="5" class="blank">
      <img src="{{ asset('images/Certificate_02.gif') }}" width="17" height="743" alt=""></td>
    <td colspan="2">
      <img src="{{ asset('images/Certificate_03.gif') }}" width="251" height="208" alt=""></td>
    <td colspan="3">
      <img src="{{ asset('images/Certificate_04.gif') }}" width="463" height="208" alt=""></td>
    <td colspan="2">
      <img src="{{ asset('images/Certificate_05.gif') }}" width="278" height="208" alt=""></td>
    <td rowspan="5">
      <img src="{{ asset('images/Certificate_06.gif') }}" width="15" height="743" alt=""></td>
  </tr>
  <tr>
    <td rowspan="2">
      <img src="{{ asset('images/Certificate_07.gif') }}" width="162" height="270" alt=""></td>
    <td colspan="5">
      <img src="{{ asset('images/Certificate_08.gif') }}" width="671" height="94" alt=""></td>
    <td rowspan="2">
      <img src="{{ asset('images/Certificate_09.gif') }}" width="159" height="270" alt=""></td>
  </tr>
  <tr>
    <td colspan="5">
      <!-- <img src="images/Certificate_10.gif" width="671" height="176" alt=""> -->
      <p width="671" height="176" style="background-color:fef3f0;font-size:20;"><center>This is to certify that 
      {{ $certificate->username }} 
        is appointed as the customer service center </center></br> 
        <center>for RBL Bank at {{ $certificate->city }}</center>
      </p>    
    </td>
  </tr>
  <tr>
    <td colspan="3">
      <img src="{{ asset('images/Certificate_11.gif') }}" width="408" height="210" alt=""></td>
    <td>
      <img src="{{ asset('images/Certificate_12.gif') }}" width="252" height="210" alt=""></td>
    <td colspan="3">
      <img src="{{ asset('images/Certificate_13.gif') }}" width="332" height="210" alt=""></td>
  </tr>
  <tr>
    <td colspan="7">
      <img src="{{ asset('images/Certificate_14.gif') }}" width="992" height="55" alt=""></td>
  </tr>
  <tr>
    <td>
      <img src="{{ asset('images/spacer.gif') }}" width="17" height="1" alt=""></td>
    <td>
      <img src="{{ asset('images/spacer.gif') }}" width="162" height="1" alt=""></td>
    <td>
      <img src="{{ asset('images/spacer.gif') }}" width="89" height="1" alt=""></td>
    <td>
      <img src="{{ asset('images/spacer.gif') }}" width="157" height="1" alt=""></td>
    <td>
      <img src="{{ asset('images/spacer.gif') }}" width="252" height="1" alt=""></td>
    <td>
      <img src="{{ asset('images/spacer.gif') }}" width="54" height="1" alt=""></td>
    <td>
      <img src="{{ asset('images/spacer.gif') }}" width="119" height="1" alt=""></td>
    <td>
      <img src="{{ asset('images/spacer.gif') }}" width="159" height="1" alt=""></td>
    <td>
      <img src="{{ asset('images/spacer.gif') }}" width="15" height="1" alt=""></td>
  </tr>
</table>
<!-- End ImageReady Slices -->

@stop