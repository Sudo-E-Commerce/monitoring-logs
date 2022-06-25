## Hướng dẫn sử dụng Sudo Monitoring Log ##

**Giới thiệu:** Đây là package dùng để ghi log vào [Hệ thống giám sát tác vụ Sudo](https://monitoring.sudo.vn).

Mặc định package giúp gửi log về cho [Hệ thống giám sát tác vụ Sudo](https://monitoring.sudo.vn) để tiện theo dõi

### Cài đặt để sử dụng ###

Chạy các lệnh:

```bash
composer require sudo/monitoring-logs

php artisan vendor:publish --provider="Sudo\MonitoringLog\Providers\SudoMonitoringLogServiceProvider"
```

### Cấu hình tham số ###

Tại ``config/SudoMonitoringLog.php`` sẽ có nội dung như sau:

```php
return [
    'host' => env('MONITORING_LOG_HOST', 'https://monitoring.sudo.vn'),
    'token' => env('MONITORING_LOG_TOKEN', ''),
];
```

Cần sửa 2 tham số tại ``.env``:

- **MONITORING_LOG_HOST**: Host hệ thống giám sát. Mặc định: ``https://monitoring.sudo.vn``
- **MONITORING_LOG_TOKEN**: Token truy cập API mà hệ thống giám sát cung cấp

### SudoMonitoringLog API ###

Các function ghi log được tích hợp sẵn tại class ``SudoMonitoringLog``:

1. Ghi log khi job thực hiện thành công:

    ```php
    /**
     * Ghi trạng thái log thành công
     * @param integer $job_item_id       ID job trên hệ thống giám sát
     * @param string  $message           Nội dung cần thông báo
     */
    SudoMonitoringLog::success($job_item_id, $message)
    ```

2. Ghi log khi job thực hiện xảy ra lỗi:

    ```php
    /**
     * Ghi trạng thái log lỗi
     * @param integer $job_item_id       ID job trên hệ thống giám sát
     * @param string  $message           Nội dung cần thông báo
     */
    SudoMonitoringLog::error($job_item_id, $message)
    ```
