## Hướng dẫn sử dụng Alepay ##

**Giới thiệu:** Đây là package dùng thanh toán qua cổng Alepay.

### Cài đặt để sử dụng ###

- Để có thể sử dụng Package cần require theo lệnh `composer require nhanchaukp/alepay`
- Publish file cấu hình tài khoản `php artisan vendor:publish --tag=alepay-config`. Thay đổi các giá trị liên quan đến tài khoản kết nối với Alepay ở file: `config/alepay`
- Sử dụng method POST để submit giá trị tới link có route name là 'app.alepay'. Chi tiết xem demo tại `/demo-alepay` đối với thanh toán Alepay.