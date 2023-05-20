<?php

namespace Nhanchaukp\Alepay\Http\Controllers;

use Nhanchaukp\Alepay\Alepay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AlepayController
{
    public function demoAlepay()
    {
        return view('Alepay::alepay');
    }

    public function alepaySetup(Request $request)
    {
        $orderInfo = [
            'amount' => 100000, 
            'orderCode' => 'FCODE123', 
            'currency' => 'VND',
            'orderDescription' => 'Test thanh toan',
            'totalItem' => 1, 
            // 'checkoutType' => 3, 
            // 'allowDomestic' => true, 

            'buyerName' => 'Chau Thai Nhan',
            'buyerEmail' => 'nhanchauthai@gmail.com',
            'buyerPhone' => '0395166587',
            'buyerAddress' => 'Vung liem',
            'buyerCity' => 'Vinh Long',
            'buyerCountry' => 'Viet Nam',

        ];
        
        $alepay = new Alepay();

        $result = $alepay->sendOrderToAlepay($orderInfo);

        if (isset($result) && !empty($result->checkoutUrl)) {
            echo '<meta http-equiv="refresh" content="0;url=' . $result->checkoutUrl. '">';
        } else {
            dd($result);
            //->message;
        }
    }

    public function alepayResult(Request $request)
    {
        dd($request->all());
    }
}