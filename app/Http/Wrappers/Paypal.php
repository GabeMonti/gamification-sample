<?php
/**
 * Created by Phpstorm.
 * Date: 18/10/18
 * Time: 15:38
 */

namespace App\Http\Wrappers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use PayPal\Api\Amount;
use PayPal\Api\Currency;
use PayPal\Api\Details;
use PayPal\Api\FlowConfig;
use PayPal\Api\InputFields;
use PayPal\Api\Item;
/** All Paypal Details class **/
use PayPal\Api\ItemList;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Api\Payer;
use PayPal\Api\PayerInfo;
use PayPal\Api\Payment;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Plan;
use PayPal\Api\Presentation;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\WebProfile;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Common\PayPalModel;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use \Exception;

class Paypal extends BaseWrapperController implements BillingWrapperInterface
{

    private $connection;

    private $cart;

    public function __construct()
    {
        $this->connect();
    }

    public function connect()
    {
        try {
            /** PayPal api context **/
            $paypal_conf = Config::get('paypal');
            $this->connection = new ApiContext(new OAuthTokenCredential(
                    $paypal_conf['client_id'],
                    $paypal_conf['secret'])
            );
            $this->connection->setConfig($paypal_conf['settings']);
        } catch (Exception $e) {
            Log::info($e);
        }
    }

    /**
     * @param $params
     */
    public function createSubscriptionPlan($params)
    {
        // Create a new billing plan
        $plan = new Plan();
        $plan->setName('App Name Monthly Billing')
            ->setDescription('Monthly Subscription to the App Name')
            ->setType('infinite');

        // Set billing plan definitions
        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Regular Payments')
            ->setType('REGULAR')
            ->setFrequency('Month')
            ->setFrequencyInterval('1')
            ->setCycles('0')
            ->setAmount(new Currency(array('value' => 9, 'currency' => 'USD')));

        // Set merchant preferences
        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl('https://website.dev/subscribe/paypal/return')
            ->setCancelUrl('https://website.dev/subscribe/paypal/return')
            ->setAutoBillAmount('yes')
            ->setInitialFailAmountAction('CONTINUE')
            ->setMaxFailAttempts('0');

        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);

        //create the plan
        try {
            $createdPlan = $plan->create($this->apiContext);

            try {
                $patch = new Patch();
                $value = new PayPalModel('{"state":"ACTIVE"}');
                $patch->setOp('replace')
                    ->setPath('/')
                    ->setValue($value);
                $patchRequest = new PatchRequest();
                $patchRequest->addPatch($patch);
                $createdPlan->update($patchRequest, $this->apiContext);
                $plan = Plan::get($createdPlan->getId(), $this->apiContext);

                // Output plan id
                echo 'Plan ID:' . $plan->getId();
            } catch (\PayPal\Exception\PayPalConnectionException $ex) {
                echo $ex->getCode();
                echo $ex->getData();
                die($ex);
            } catch (Exception $ex) {
                die($ex);
            }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            Log::info($ex);
        } catch (Exception $ex) {
            Log::info($ex);
        }
    }

    /**
     * @param $params
     * @return string
     */
    private function createCompanyProfile($params)
    {
        $flowConfig = new FlowConfig();
        $flowConfig->setLandingPageType("Billing")
            ->setUserAction("commit")
            ->setReturnUriHttpMethod("GET");

        $presentation = new Presentation();
        $presentation
            ->setBrandName($params['companyName'])
            ->setLocaleCode($params['language'])
            ->setReturnUrlLabel("Return")
            ->setNoteToSellerLabel("Thanks!");

        $inputFields = new InputFields();
        $inputFields->setAllowNote(true)
            ->setNoShipping(1)
            ->setAddressOverride(0);

        $webProfile = new WebProfile();
        $webProfile->setName($params['companyName'] . uniqid())
            ->setFlowConfig($flowConfig)
            ->setPresentation($presentation)
            ->setInputFields($inputFields)
            ->setTemporary(true);

        try {
            $createProfileResponse = $webProfile->create($this->connection);
        } catch (PayPalConnectionException $ex) {
            Log::info($ex);
        }
        return $createProfileResponse->getId();
    }

    /**
     * @param $params
     * @return array
     */
    public function setPayment($params)
    {
        $companyPaypalProfile = $this->createCompanyProfile($params);

        $currency= $params['currency'];
        $returnUrl = $params['apiUrl'].'/billing/return/purchase/success';
        $cancelUrl = $params['apiUrl'].'/billing/return/purchase/cancel';

        $payerInfo = new PayerInfo();
        $payerInfo->setPayerId($params['userId']);
        $payerInfo->setEmail($params['email']);

        $payer = new Payer();
        $payer->setPayerInfo($payerInfo);
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();

        $item_1->setName($params['productTitle'])
            ->setCurrency($currency)
            ->setQuantity(1)
            ->setPrice($params['total'])
            ->setDescription($params['productDescription']);

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency($currency);
        $amount->setTotal($params['total']);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list);
//            ->setDescription('Transaction Description');


        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($returnUrl);
        $redirectUrls->setCancelUrl($cancelUrl);


        $payment = new Payment();

        $payment->setIntent('sale');
        $payment->setPayer($payer);
        $payment->setRedirectUrls($redirectUrls);
        $payment->setTransactions(array($transaction));
        $payment->getToken();
        $payment->setExperienceProfileId($companyPaypalProfile);
        $payment->getId();

        try {
            $payment->create($this->connection);
        } catch (\PayPal\Exception\PayPalConnectionException $e) {
            Log::info($e->getCode());
            Log::info($e->getData());
        }

        return [
            "token"=>$payment->getToken(),
            "approvalUrl"=>$payment->getApprovalLink(),
            "id"=>$payment->getId(),
            "status"=>$payment->getState()
        ];
    }

    /**
     * @param $params
     * @return \Illuminate\Http\JsonResponse|Payment
     */
    public function executePayment($params)
    {
        $payment = Payment::get($params['paymentId'], $this->connection);

        $execution = new PaymentExecution();
        $execution->setPayerId($params['payerId']);

        try {
            $payment->execute($execution, $this->connection);

            try {
                $payment = Payment::get($params['paymentId'], $this->connection);
            } catch (\PayPal\Exception\PayPalConnectionException $e) {
                Log::info($e->getCode());
                Log::info($e->getData());
            }
        } catch (\PayPal\Exception\PayPalConnectionException $e) {
            Log::info($e->getCode());
            Log::info($e->getData());
        }

        return $payment;
    }

    /**
     * @param $payment_id
     * @return Payment|string
     */
    public function getPaymentStatus($payment_id)
    {
        try {
            $payment = Payment::get($payment_id, $this->connection);
        } catch (\Exception $ex) {
            Log::info($ex);
            $payment = $ex->getMessage();
        }
        return $payment;
    }

}