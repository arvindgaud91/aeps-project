<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="AgentsCtrl" class="head-weight">
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default panel-border-color panel-border-color-primary">
      <div class="panel-heading panel-heading-divider">Drivers <span class="panel-subtitle">List</span></div>
      <div class="panel-body">
        
          <div class="row">
            <div class="col-md-12">
             Mantra <a class="btn btn-xs btn-success" href="/driver/Mantra.zip" download>Download</a>
            </div>
            <!-- <br><br><br>
            <div class="col-md-12">
              Morpho <a href="/driver/Appletless_2.20.6.14.zip" download>Download</a>
             
            </div> -->
          </div>
          <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-offset-1 col-md-9 col-sm-12 m-t-15" >
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/E8BxzqocLuA?ecver=2&rel=0"></iframe>
                </div>  
            </div>
          </div>
      </div>
    </div>
    
  </div>
  
</div>
</div>
@stop
@section('javascript')
<script>
angular.module('DIPApp')
.controller('AgentsCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    }])
</script>
@stop
