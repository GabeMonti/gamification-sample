<?php
/**
 * Created by PhpStorm.
 * Date: 8/27/18
 * Time: 7:43 PM
 */

if( $roleId != 'undefined' ){
    $redirect = $origin . '/' . strtolower($companyName) . "/register/" . $roleId;
    if( isset($socialId) ){
        $redirect .= "?sId=" . $socialId . "&name=" .$name . "&email=" .$email . "&provider=" .$provider  ;
    }
} else {
    $redirect = $origin . '/' . strtolower($companyName) . "/login";
}

header("location:$redirect");