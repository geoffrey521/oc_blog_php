FROM php:8.0-apache
RUN apt-get update && apt-get install -y --no-install-recommends ssmtp mailutils vim curl debconf subversion git apt-transport-https apt-utils \
build-essential locales acl mailutils wget nodejs \
gnupg gnupg1 gnupg2 \
zlib1g-dev zlib1g-dev libicu-dev g++
RUN docker-php-ext-install mysqli pdo_mysql && docker-php-ext-enable mysqli pdo_mysql
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
COPY config/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

# set up sendmail config, see http://linux.die.net/man/5/ssmtp.conf for options
RUN echo "hostname=localhost.localdomain" > /etc/ssmtp/ssmtp.conf
RUN echo "mailhub=maildev" >> /etc/ssmtp/ssmtp.conf
# The above 'maildevt3' is the name you used for the link command
# in your docker-compose file or docker link command.
# Docker automatically adds that name in the hosts file
# of the container you're linking MailDev to.

# Set up php sendmail config
RUN echo "sendmail_path=sendmail -i -t" >> /usr/local/etc/php/conf.d/php-sendmail.ini

# Fully qualified domain name configuration for sendmail on localhost.
# Without this sendmail will not work.
# This must match the value for 'hostname' field that you set in ssmtp.conf.
RUN echo "localhost localhost.localdomain" >> /etc/hosts