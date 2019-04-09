<?php
/**
 * Created by PhpStorm.
 * Date: 8/27/18
 * Time: 5:50 PM
 */


$redirect = $origin . '/' . strtolower($companyName) . "/login/" . $token;
header("location:$redirect");