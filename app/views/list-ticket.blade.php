<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="listTicketCtrl"  class="head-weight">
  <div class="row">
    <div class="col-md-12">
       <div class="panel panel-default">
        <!-- Page Title -->
        <div class="panel-heading">List Ticket</div>
        <!-- /Page Title --> 
        <!-- Page Content -->
        <div class="tab-container ">
          <div class="tab-content ">
             <div class="panel panel-default panel-default panel-border-color panel-border-color-primary">
                 <div class="panel-body table-responsive">
                    <table class="table table-striped table-borderless" >
                      <thead>
                        <tr>
                          <th>Ticket No.</th>
                          <th width="40%">Title</th>
                          <th>Status</th>
                          <th>Last Updated</th>
                          <th>Created Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><a href="#">1234567890</a></td>
                          <td>List Ticket Process</td>
                          <td>Pending</td>
                          <td>2017-11-27</td>
                          <td>2017-11-27</td>
                        </tr>
                        <tr>
                          <td><a href="#">1234567890</a></td>
                          <td>List Ticket Process</td>
                          <td>Pending</td>
                          <td>2017-11-27</td>
                          <td>2017-11-27</td>
                        </tr>
                        <tr>
                          <td><a href="#">1234567890</a></td>
                          <td>List Ticket Process</td>
                          <td>Pending</td>
                          <td>2017-11-27</td>
                          <td>2017-11-27</td>
                        </tr>
                        <tr>
                          <td><a href="#">1234567890</a></td>
                          <td>List Ticket Process</td>
                          <td>Pending</td>
                          <td>2017-11-27</td>
                          <td>2017-11-27</td>
                        </tr>
                        <tr>
                          <td><a href="#">1234567890</a></td>
                          <td>List Ticket Process</td>
                          <td>Pending</td>
                          <td>2017-11-27</td>
                          <td>2017-11-27</td>
                        </tr>
                        <tr>
                          <td><a href="#">1234567890</a></td>
                          <td>List Ticket Process</td>
                          <td>Pending</td>
                          <td>2017-11-27</td>
                          <td>2017-11-27</td>
                        </tr>
                        <tr>
                          <td><a href="#">1234567890</a></td>
                          <td>List Ticket Process</td>
                          <td>Pending</td>
                          <td>2017-11-27</td>
                          <td>2017-11-27</td>
                        </tr>
                        <tr>
                          <td><a href="#">1234567890</a></td>
                          <td>List Ticket Process</td>
                          <td>Pending</td>
                          <td>2017-11-27</td>
                          <td>2017-11-27</td>
                        </tr>
                      </tbody>
                    </table>
                   </div>
                 </div>
             </div>
          </div>
        </div>
        <!-- /Page Content -->
       </div>
    </div>
  </div>
</div>
@stop
@section('javascript')
<script>
angular.module('DIPApp')

  .controller('listTicketCtrl', ['$scope', '$http','Upload','fileUpload', function ($scope, $http,Upload,fileUpload) {
  window.s = $scope
  
}])
</script>
@stop