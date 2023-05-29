# Big Challenge backend

This is a project developed with the purpose of learning Laravel.
The project must be ran with Laravel Sail, and you must create a mysql database named `big_challenge_backend` (to do this you can connect to the mysql container using `sail mysql`).
Run these commands to start the project (make sure to install and run docker first):

```
git clone git@github.com:sgoycoechea-lightit/big-challenge-backend.git
cd big-challenge-backend
git checkout develop
cp .env.example .env

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
    
sail art key:generate
sail art migrate:fresh --seed
sail up
```
