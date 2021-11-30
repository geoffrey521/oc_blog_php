# oc_blog_php

# Extern library used
* Twig
* SCSS
* Bootstrap

* Whoops
* var-dumper
* Grumphp
* Maildev

# Commits Messages
* Work type is indicated at the beginning of the line
* Followed by the associated issue reference preceded by a "/"
* The name of the feature on which we are working can optionally be displayed preceded by a first "-"
* A short description of the content ends the commit preceded by a second "-"
* for exemple: 
* "feature / 2 - base setup - import template files and configure twig"

# Initialise project
## Requirements 
* Composer
* Docker
* npm

## Steps
* First thing, you need to pull project from the repository
* Use terminal et past this command lines:  
npm install  
npm update  
composer install  
composer update  
composer dumpautoload  
docker-compose up --build
* After, you need to go on the phpmyadmin interface via the address: localhost:8081
* user: root password: root (you can change password in the docker-compose.yml file)
* Click on import button, click onb "choose a file" and select "import.sql" at the base of repository