<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PortWallet\Exceptions\PortWalletClientException;
use PortWallet\PortWallet;
use PortWallet\PortWalletClient;

class PortwalletController extends Controller
{
  private $api_url = '';
  private $app_key = '';
  private $secret_key = '';
  private $authorization = '';
  private $courses = '';
  const $environment;

    public function __construct()
    {

      if ($environment == "DEV") {
			$this->api_url = 'https://api-sandbox.portwallet.com/payment/v2/ ';
			$this->app_key = '23b844c80146b36df469b0cf63d5080e';
			$this->secret_key = 'a017c8cadeb13d7a9a72ec902573c287';
		} else {
			$this->api_url = 'https://api.portwallet.com/payment/v2/invoice';
			$this->app_key = 'fde409259497bab63ce09e133dbdf0d7';
			$this->secret_key = '1bd2edd201a0f61832af2c15e4344724';
		}

		$this->authorization = "Authorization: Bearer " . base64_encode($this->app_key . ":" . md5($this->secret_key . time()));
    }
    public function checkout($from) {
  if ($this->session->userdata('user_type') !== "USER" && $this->session->userdata('user_type') !== "ADMIN" && $this->session->userdata('user_type') !== "SUPER_ADMIN") {
    redirect('/home/login');
  } else {

    $courses_in_string = '';
    if(isset($this->session->userdata('checkout_info')['courses'])){
      for ($i = 0; $i < count($this->session->userdata('checkout_info')['courses']); $i++) {
        /* USD Currecny convertion code*/
        if(get_user_country() == 'bd' || get_user_country() == 'BD'){
          $amount_to_pay_now = $this->session->userdata('checkout_info')['courses'][$i]['amount_to_pay_now'];

        }else{
          $amount_to_pay_now = get_bdt_price($this->session->userdata('checkout_info')['courses'][$i]['amount_to_pay_now']);
        }

        // $courses_in_string .= $this->session->userdata('checkout_info')['courses'][$i]['title'] . ' ( ' . $this->session->userdata('checkout_info')['courses'][$i]['amount_to_pay_now'] . ' BDT )';
        $courses_in_string .= $this->session->userdata('checkout_info')['courses'][$i]['title'] . ' ( ' . $amount_to_pay_now . ' BDT )';

        if (($i + 1) < count($this->session->userdata('checkout_info')['courses'])) {
          $courses_in_string .= ' , ';
        }

      }
    }


    $user= User::find($id);

    /* Currency conversiion code*/

      $total_amount_to_pay = ((float) $this->session->userdata('checkout_info')['total_amount_to_pay']);


    // debug($total_amount_to_pay);
    // exit;
    $post = [
      'order' => [
        // 'amount' => (float) $this->session->userdata('checkout_info')['total_amount_to_pay'],
        'amount' => $total_amount_to_pay,
        'currency' => 'BDT',
        'redirect_url' => site_url('portwallet/portwallet_verify_transaction/' . $from),
      ],
      'product' => [
        'name' => "Course payment",
        'description' => "Course payment for " . $courses_in_string,
      ],
      'billing' => [
        'customer' => [
          'name' => $this->session->userdata('name'),
          'email' => $this->session->userdata('email'),
          'phone' => (($user->phone == '') || (strlen($user->phone) <= 8)) ? '01766343434' : $user->phone,
          'address' => [
            'street' => 'Hayat Rose Park, Level 5, House No 16 Main Road, Bashundhara Residential Area',
            'city' => 'Dhaka',
            'state' => 'Dhaka',
            'zipcode' => '1229',
            'country' => 'BD',
          ],
        ],
      ],
    ];

    // STart CURL

    // $ch = curl_init('https://api.portwallet.com/payment/v2/invoice');

    $ch = curl_init($this->api_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $this->authorization));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));

    // execute!
    $response = curl_exec($ch);

    // close the connection, release resources used
    curl_close($ch);
    // END CURL;

    $responseData = json_decode($response);
    if ($responseData->result == "success") {
      $this->session->set_userdata('portwallet_invoice_id', $responseData->data->invoice_id);
      $this->session->set_userdata('portwallet_invoice_amount', $responseData->data->order->amount);


      redirect($responseData->data->action->url);
    } else {
      if ($responseData->error->property == "billing.customer.phone") {
        $this->session->set_flashdata('portwallet_error', 'Please save your valid phone number.');
      } else {
        $this->session->set_flashdata('portwallet_error', $responseData->error->explanation);
      }

      if ($from == 'shopping_cart') {

        redirect(base_url('home/shopping_cart'));
      } else {
        // debug($responseData);
        redirect(base_url('user/payment_history'));
      }
    }
    // redirect($responseData->data->action->url);

  }
}

public function portwallet_verify_transaction($from) {

  if ($this->session->userdata('portwallet_invoice_id') === null) {
    redirect(base_url('home/page_not_found'));
  }

  // echo $this->api_url . '/ipn/' . $this->session->userdata('portwallet_invoice_id') . '/' . $this->session->userdata('portwallet_invoice_amount'). "<br />";
  // exit;
  $ch = curl_init($this->api_url . '/ipn/' . $this->session->userdata('portwallet_invoice_id') . '/' . $this->session->userdata('portwallet_invoice_amount'));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $this->authorization));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  curl_close($ch);

  $responseData = json_decode($response);

  if ($responseData->result === 'success') {
    $current_time = date("Y-m-d H:i:s");
    if ($responseData->data->transactions[0]->status === 'ACCEPTED') {
      // debug($this->session->userdata('checkout_info')['courses']);

      $transaction_table_data = array(
        'invoice_id' => $responseData->data->invoice_id,
        'amount' => $responseData->data->order->amount,
        'status' => "ACCEPTED",
        'created_at' => $current_time,
      );

      $transaction_insert_response = $this->crud_model->insert_into_transaction($transaction_table_data);

      if ($transaction_insert_response["success"]) {

        foreach ($this->session->userdata('checkout_info')['courses'] as $course) {

          $enrollment_id = is_an_user_already_enrolled_in_a_course($this->session->userdata('user_id'), $course['id']);

           /* Currency conversiion code*/
           if(get_user_country() == 'bd' || get_user_country() == 'BD'){
            $amount_to_pay = ($course['amount_to_pay']);
           }else{
            $amount_to_pay = get_bdt_price($course['amount_to_pay']);

           }


          if (!$enrollment_id) {
            if (isset($course['coupon_id'])) {
              $enrollment_response = $this->crud_model->enroll_an_user_in_a_course($this->session->userdata('user_id'), $course['id'], $course['coupon_id'], $amount_to_pay);

            } else {
              $enrollment_response = $this->crud_model->enroll_an_user_in_a_course($this->session->userdata('user_id'), $course['id']);

            }

            if ($enrollment_response['success']) {
              $enrollment_id = $enrollment_response['enrolled_id'];
            } else {
              $this->session->set_flashdata('portwallet_error', 'Oops! something went wrong.');
            }

          }

          /* Currency conversiion code*/
          if(get_user_country() == 'bd' || get_user_country() == 'BD'){
            $amount_to_pay_now = ($course['amount_to_pay_now']);
          }else{
            $amount_to_pay_now = get_bdt_price($course['amount_to_pay_now']);

          }

          if($responseData->data->order->amount < $amount_to_pay_now){
            $enrollment_payment_data = array(
              'invoice_id' => $responseData->data->invoice_id,
              'payment_method' => "Portwallet",
              'enrollment_id' => $enrollment_id,
              'amount' => $responseData->data->order->amount,
              'status' => "ACCEPTED",
              'created_at' => $current_time,
            );

            $enrollment_payment_response = $this->crud_model->insert_into_enrollment_payment($enrollment_payment_data);
            $this->session->set_flashdata('portwallet_error', 'Oops! your given amount and our course amount doesn"t match.please provide a valid amount.');
          }else{
            $enrollment_payment_data = array(
              'invoice_id' => $responseData->data->invoice_id,
              'payment_method' => "Portwallet",
              'enrollment_id' => $enrollment_id,
              'amount' => $amount_to_pay_now,
              'status' => "ACCEPTED",
              'created_at' => $current_time,
            );

            $enrollment_payment_response = $this->crud_model->insert_into_enrollment_payment($enrollment_payment_data);
          }

          // enrollment_payment - invoice_id, payment_method, enrollment_id, amount, status, created_at, last_modified



          if (!$enrollment_payment_response['success']) {
            $this->session->set_flashdata('portwallet_error', 'Oops! something went wrong.');
          }

        }

      } else {
        $this->session->set_flashdata('portwallet_error', "Failed to make transaction!");
      }

    } else {
      $this->session->set_flashdata('portwallet_error', 'Payment failed!');
    }

    if (!$this->session->flashdata('portwallet_error')) {
      $this->session->set_flashdata('portwallet_success', 'Payment successful!');
    }

    if ($from == 'shopping_cart') {
      redirect(base_url('home/shopping_cart?payment-status="successful"'));
    } else {
      redirect(base_url('user/payment_history'));
    }
  }
  // debug($responseData);
}
}
