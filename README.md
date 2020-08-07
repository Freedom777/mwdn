**Install**

1. Run "git clone https://github.com/Freedom777/mwdn"
2. Run "composer install" from folder, where you copied files.
3. Create database
4. Change .env file, according to environment settings, example
APP_URL=http://mwdn.test  
DB_CONNECTION=mysql  
DB_HOST=127.0.0.1  
DB_PORT=3306  
DB_DATABASE=mwdn  
DB_USERNAME=root  
DB_PASSWORD=

5. Run "php artisan migrate".
6. Enjoy 8) 

**Usage**

Example of use with POST method on http:://mwdn.test

{
    "url": "http://google.com",
    "expired_at": "2020-08-07 12:00:00"
}

or without expired_at

{
    "url": "http://google.com"
} 

Example of response

{
    "success": true,
    "newUrl": "http://mwdn.test/wqy6uixbn"
}

Example of use with GET method

http://mwdn.test/wqy6uixbn

Result:

redirecting to google.com OR 404 if expired
