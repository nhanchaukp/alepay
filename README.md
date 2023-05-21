# Tích hợp thanh toán Alepay cho Laravel

### Hướng dẫn cài đặt

Cài đặt package 
```
composer require nhanchaukp/alepay
```
Publish file cấu hình tài khoản 
```
php artisan vendor:publish --tag=alepay-config
```
Thay đổi các giá trị liên quan đến tài khoản kết nối với Alepay ở file: `config/alepay`

### Hiển thị trang demo
Thêm vào routes web.php

```
use Nhanchaukp\Alepay\Facades\Alepay;
...

Alepay::routes();
```
Truy cập demo tại `domain.com/demo-alepay`.

### Các phương thức
Tạo yêu cầu và lấy link thanh toán
```
use Nhanchaukp\Alepay\Facades\Alepay;
...

$orderInfo = [
    'amount' => 100000,
    'orderCode' => 'FCODE123',
    'currency' => 'VND',
    'orderDescription' => 'Test thanh toán Alepay',
    'totalItem' => 1,
    'checkoutType' => 3,
    'allowDomestic' => true,

    'buyerName' => 'Nhan Chau KP',
    'buyerEmail' => 'demoalepay@gmail.com',
    'buyerPhone' => '0929389359',
    'buyerAddress' => 'Vung liem',
    'buyerCity' => 'Vinh Long',
    'buyerCountry' => 'Viet Nam',

];

$result = Alepay::requestPayment($orderInfo);
```

Lấy thông tin giao dịch
```
use Nhanchaukp\Alepay\Facades\Alepay;
...

public function alepayResult(Request $request)
{
    if ($request->errorCode == '000') {
        // success
        $info = Alepay::getTransactionInfo($request->transactionCode);
        dd($info);
    } else {
        // error
    }
}
```

Xác thực dữ liệu webhook
```
use Nhanchaukp\Alepay\Facades\Alepay;
...

public function webhook(Request $request)
{
    return response()->json([
        'raw' => $request->all(),
        'verify' => Alepay::verifyTransaction($request->all())
    ]);
}
```