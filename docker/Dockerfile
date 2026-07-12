FROM php:8.2-apache

# Enable mod_rewrite (for .htaccess SEO/redirect rules)
RUN a2enmod rewrite headers deflate expires

# Apache ServerName (silences warning)
RUN echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername

EXPOSE 80
