# commuter-api
## To run locally

* Init the storage. This will create a sqlite database file at `/tmp/db.sq3`
```
APP_ENV=dev php scripts/install.php
```
* Run the server
```
APP_ENV=dev php -S localhost:8888 -t public/ public/app_local.php
```
