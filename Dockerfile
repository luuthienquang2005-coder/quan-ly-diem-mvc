# Sử dụng image chuẩn từ Docker Hub theo gợi ý của đề thi
FROM php:apache

# Bật module rewrite của Apache cho cấu trúc MVC
RUN a2enmod rewrite

# Cài đặt extension mysqli để PHP kết nối được với MySQL
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli

# Cấp quyền cho thư mục web
RUN chown -R www-data:www-data /var/www/html
