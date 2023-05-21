<?php

namespace Nhanchaukp\Alepay;

class Alepay
{
	protected $env;

	protected $config;

	protected $baseURL = [
		'sandbox' => 'https://alepay-v3-sandbox.nganluong.vn/api/v3/checkout',
		'live' => 'https://alepay-v3.nganluong.vn/api/v3/checkout'
	];

	protected $api = [
		'requestPayment' => '/request-payment',
		'getTransactionInfo' => '/get-transaction-info',
		'getInstallmentInfo' => '/get-installment-info',
		'getListBanks' => '/get-list-banks'
	];

	public function __construct()
	{
		$this->config = config('alepay');
		$this->env = $this->config['env'];
	}

	/**
	* Request checkout url from order info
	* @param array $data
	*/

	public function requestPayment($data)
	{
		return $this->sendRequestToAlepay($data, $this->api['requestPayment']);
	}

	/**
	* Get transaction info from Alepay
	* @param string $transactionCode
	*/

	public function getTransactionInfo($transactionCode)
	{
		$data = ['transactionCode' => $transactionCode];

		return $this->sendRequestToAlepay($data, $this->api['getTransactionInfo']);
	}

	/**
	 * Get installment info
	 * @param int $amount
	 * @param string|null $currencyCode
	 */
	public function getInstallmentInfo($amount, $currencyCode = 'VND')
	{
		$data = [
			'amount' => $amount,
			'currencyCode' => $currencyCode
		];

		return $this->sendRequestToAlepay($data, $this->api['getInstallmentInfo']);
	}

	/**
	 * Get list banks for method ATM, IB, QRCODE
	 */
	public function getListBanks()
	{
		return $this->sendRequestToAlepay([], $this->api['getListBanks']);
	}

	/**
	 * Verify data for webhook
	 * @param array $data
	 */
	public function verifyTransaction($data)
	{
		$transaction_info = $data['transactionInfo'] ?? null;
		$orderCode = $transaction_info['orderCode'] ?? null;
		$amount = $transaction_info['amount'] ?? null;
		$transactionCode = $transaction_info['transactionCode'] ?? null;
		$checksum = $data['checksum'] ?? null;
		if (!$orderCode or !$amount or !$transactionCode or !$checksum) {
			return false;
		}

		return $checksum == md5($orderCode . $amount . $transactionCode . $this->config['checksumKey']);
	}

	private function sendRequestToAlepay($data, $endpoint)
	{
		$data['tokenKey'] = $this->config['tokenKey'];
		if ($endpoint == $this->api['requestPayment']) {
			$data['returnUrl'] = $this->config['returnUrl'];
			$data['cancelUrl'] = $this->config['cancelUrl'];
		}
		ksort($data); //sort alphabet array key
		$signature = hash_hmac('sha256', $this->build_query_data($data), $this->config['checksumKey']);
		$data['signature'] = $signature;

		$data_string = json_encode($data);
		$url = $this->baseURL[$this->env] . $endpoint;
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

		return json_decode($result);
	}

	private function build_query_data($array)
	{
		return urldecode(http_build_query(array_map(function ($value) {
			if ($value === true) {
				return 'true';
			}
			if ($value === false) {
				return 'false';
			}

			return $value;
		}, $array)));
	}

	public function routes()
	{
		require __DIR__ . '/routes/routes.php';
	}
}
