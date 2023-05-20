<?php

namespace Nhanchaukp\Alepay;

use Illuminate\Support\Arr;

class Alepay
{
	protected $env;

	protected $baseURL = [
		'sandbox' => 'https://alepay-v3-sandbox.nganluong.vn/api/v3/checkout',
		'live' => 'https://alepay-v3.nganluong.vn/api/v3/checkout'
	];

	protected $api = [
		'requestPayment' => '/request-payment',
		'calculateFee' => '/checkout/v3/calculate-fee',
		'getTransactionInfo' => '/get-transaction-info',
		'requestCardLink' => '/checkout/v3/request-profile',
		'tokenizationPayment' => '/checkout/v3/request-tokenization-payment',
		'tokenizationPaymentDomestic' => '/checkout/v3/request-tokenization-payment-domestic',
		'cancelCardLink' => '/checkout/v3/cancel-profile',
		'requestCardLinkDomestic' => '/alepay-card-domestic/request-profile',
	];

	public function __construct()
	{
		// header('Access-Control-Allow-Origin: *');
		// header('Access-Control-Allow-Credentials: true');
		// header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
		// header('Access-Control-Max-Age: 1000');
		// header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

		// Require curl and json extension
		if (!function_exists('curl_init')) {
			throw new Exception('Alepay needs the CURL PHP extension.');
		}
		if (!function_exists('json_decode')) {
			throw new Exception('Alepay needs the JSON PHP extension.');
		}

		$this->env = config('alepay.env');
	}

	/*
	* sendOrder - Send order information to Alepay service
	* @param array|null $data
	*/

	public function sendOrderToAlepay($data)
	{
		// get demo data
		// $data = $this->createCheckoutData();
		// $data['cancelUrl'] = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/demo-alepay';
		$url = $this->baseURL[$this->env] . $this->api['requestPayment'];
		$result = $this->sendRequestToAlepay($data, $url);

		return $result;
	}

	/*
	* get transaction info from Alepay
	* @param array|null $data
	*/

	public function getTransactionInfo($transactionCode)
	{
		// demo data
		$data = ['transactionCode' => $transactionCode];
		$url = $this->baseURL[$this->env] . $this->URI['getTransactionInfo'];
		$result = $this->sendRequestToAlepay($data, $url);
		if ($result->errorCode == '000') {
			$dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);

			return  $dataDecrypted;
		} else {
			return json_encode($result);
		}
	}

	private function sendRequestToAlepay($data, $url)
	{
		$config = config('alepay');
		$data = array_merge($data, [
			'tokenKey' => $config['tokenKey'],
			'returnUrl' => $config['returnUrl'],
			'cancelUrl' => $config['cancelUrl']
		]);
        ksort($data); //sort alphabet array key
		$signature = hash_hmac('sha256', Arr::query($data), $config['checksumKey']);
		$data['signature'] = $signature;
		dump($data);

		$data_string = json_encode($data);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt(
			$ch,
			CURLOPT_HTTPHEADER,
			[
				'Content-Type: application/json'
			]
		);
		$result = curl_exec($ch);
        curl_close($ch);

		dd(json_decode($result));

		return json_decode($result);
	}
}
