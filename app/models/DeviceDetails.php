<?php

class DeviceDetails extends \Eloquent {
	protected $fillable = ['user_id', 'device_make', 'device_model', 'device_serial','created_at'];
	protected $table='device_details';
}
