<?php
/**
 * Created by PhpStorm.
 * Date: 18/10/18
 * Time: 15:43
 */


namespace App\Http\Wrappers;


interface BillingWrapperInterface
{

    public function connect();
    public function createSubscriptionPlan($params);
    public function setPayment($params);
    public function executePayment($params);
    public function getPaymentStatus($payment_id);

}