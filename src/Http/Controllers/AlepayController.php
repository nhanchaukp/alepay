<?php

namespace Nhanchaukp\Alepay\Http\Controllers;

use Nhanchaukp\Alepay\Facades\Alepay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class AlepayController
{
	public function demoAlepay()
	{
		return view('Alepay::alepay');
	}

	public function alepaySetup(Request $request)
	{
		$orderInfo = $request->all();
		$orderInfo['checkoutType'] = (int)$request->checkoutType;
		$orderInfo['allowDomestic'] = (bool)$request->allowDomestic;

		$result = Alepay::requestPayment($orderInfo);

		if (isset($result) && !empty($result->checkoutUrl)) {
			return redirect()->to($result->checkoutUrl);
		} else {
			dd($result);
		}
	}

	public function alepayResult(Request $request)
	{
        $info = Alepay::getTransactionInfo($request->transactionCode);
		dump($info);
	}

	public function webhook(Request $request)
	{
		Log::debug(json_encode($request->all()));

		return response()->json([
			'raw' => $request->all(),
			'verify' => Alepay::verifyTransaction($request->all())
		]);
	}
}
