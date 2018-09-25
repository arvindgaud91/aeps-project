<?php

class FileUpload extends \Eloquent {
	protected $fillable = ['name', 'link'];
	protected $table = 'files'; 
}