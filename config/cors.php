<?php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'], // Cho phép tất cả các phương thức HTTP
    'allowed_origins' => ['*'], // Chỉ cho phép React truy cập
    'allowed_origins_patterns' => [], // Không cần thiết nếu đã chỉ định allowed_origins
    'allowed_headers' => ['*'], // Cho phép tất cả các header
    'exposed_headers' => [], // Không cần thiết nếu không cần expose header
    'max_age' => 0, // Không giới hạn thời gian cache
    'supports_credentials' => true, // Nếu cần gửi cookie hoặc thông tin xác thực
];