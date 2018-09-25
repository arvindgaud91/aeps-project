<?php

namespace Acme\Auth;
use \Firebase\JWT\JWT;
use \User;
use \Vendor;
use \Hash;
use \ExpiredToken;
use \Request;


class Auth {

  protected $user = null;
  static $instancertrt = null;

  function __construct() {
    try {

      $key = getJWTKey();
      $tracker = \Cookie::get('tracker');
      if (! $tracker) {
        $authHeader = Request::header('auth');
        if ($authHeader) $tracker = $authHeader;
      }
      if (! $tracker) {
        return true;
      }
      $expiredToken = ExpiredToken::where('token', $tracker)->first();
      if ($expiredToken) return true;
      $decoded = JWT::decode($tracker, $key, ['HS256']);
      if (! $decoded) {
        return true;
      }
      $user = User::find($decoded->user_id);
      $this->user = $user;
      if ($user->status == 0) $this->user = null;
      if ($user->type != 4) $this->user = null;
      if (property_exists($decoded, 'web_auth_code') && ($user->web_auth_code !== $decoded->web_auth_code)) $this->user = null;
      if (property_exists($decoded, 'mobile_auth_code') && ($user->mobile_auth_code !== $decoded->mobile_auth_code)) $this->user = null;

    } catch (Exception $e) {
      return true;
    } finally {
      return true;
    }
  }

  public static function validate ($credentials)
  {
    if (! $credentials['phone_no'] || ! $credentials['password']) {
      return FALSE;
    }

  $user = \User::where('email', $credentials['phone_no'])->orwhere('phone_no', $credentials['phone_no'])->first();
    if (! $user) {
      return FALSE;
    }
    if ($user->type != 4 && $user->type != 3) {
      return FALSE;
    }
    if (! \Hash::check($credentials['password'], $user->password)) {
      return FALSE;
    }
    return $user;
  }


  public static function validatev3 ($credentials)
  {
    if (! $credentials['phone_no']) {
      return FALSE;
    }

  $user = \User::where('phone_no', $credentials['phone_no'])->first();
    if (! $user) {
      return FALSE;
    }
    if ($user->type != 4 && $user->type != 3) {
      return FALSE;
    }
   
    return $user;
  }
  

  public static function validateToken ($token) 
  {
    $key = getJWTKey();
    $tracker = $token;

    if (! $tracker) {
      return \Response::json(['message' => 'Missing token']);
    }

    $expiredToken = ExpiredToken::where('token', $tracker)->first();
    if ($expiredToken) return null;

    $decoded = JWT::decode($tracker, $key, ['HS256']);
    if (! $decoded) {
      return null;
    }

    $user = User::find($decoded->user_id);
    $user->vendorDetails = Vendor::where('user_id', $user->id)->first();

    if ($user->status == 0) $user = null;
    // if ($user->type != 4) $user = null;
    
    if (property_exists($decoded, 'web_auth_code') && ($user->web_auth_code !== $decoded->web_auth_code)) $user = null;
    if (property_exists($decoded, 'mobile_auth_code') && ($user->mobile_auth_code !== $decoded->mobile_auth_code)) $user = null;

    return $user;

  }

  public static function attempt ($credentials)
  {
    $user = static::validate($credentials);
    if (! $user) return false;
    if ($user->status == 0) return false;
    return static::login($user);
  }

  public static function login ($user)
  {
    $key = getJWTKey();
    $token = [
      'iss' => getBU(),
      'iat' => (new \DateTime)->getTimestamp(),
      'user_id' => $user->id,
      'name' => $user->name,
      'privileges' => 'consumer',
      'web_auth_code' => $user->web_auth_code
    ];
    $jwt = JWT::encode($token, $key);
    \Cookie::queue('tracker', $jwt, 60*24*7);
    return $jwt;
  }

  public static function generateLoginToken ($user)
  {
    $key = getJWTKey();
    $token = [
      'iss' => getBU(),
      'iat' => (new \DateTime)->getTimestamp(),
      'user_id' => $user->id,
      'name' => $user->name,
      'privileges' => 'consumer',
      'mobile_auth_code' => $user->mobile_auth_code
    ];
    return JWT::encode($token, $key);
  }

  public static function user ()
  {
    $instance = static::getInstance();
    return $instance->user;
  }

  public static function logout ()
  {
    $instance = static::getInstance();
    $instance->user = null;
    \Cookie::queue(\Cookie::forget('tracker'));
    return TRUE;
  }

  public static function getInstance ()
  {
    return static::$instancertrt
      ? static::$instancertrt
      : static::setInstance();
  }

  public static function setInstance ()
  {
    return static::$instancertrt = new self();
  }

  public static function distributordetails($id)
  {//dd($id);
  $Vendor=Vendor::where('user_id',$id)->first();
  //dd($Vendor);
return $Vendor;
  } 

}
