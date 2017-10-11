# Contact registry POC

## Development set up
### System Requirements:
- Docker 17.03.1-ce or greater
- Docker compose 1.13.0 or greater

### Setup
#### Initial set up
Run the following command to build and set up all the require containers:

- `docker-compose up -d --build`

This will install and build the following containers:
- App container: Is where the application lives and all the resources and databases will be trigger and handle from there.
- App Database container
- Server container
- Original Data container

#### Configuration
To configure the application, connect to the main container:

- `docker-compose exec app bash`

Run the following commands to set up the development enviroment in your local machine:

- `copy('.env.example', '.env')`
- `composer install`
- `php artisan cache:clear`
- `php artisan migrate`
- `php artisan db:seed`

This will install all required depencies for the project, and migrate seed data for development proposes.


#### Troubleshooting
In some cases there it may be need to fix the folder permissions since the docker container can access it:

- `chown -R www-data storage`
- `chown -R www-data bootstrap/cache`
- `chmod -R 0770 storage`
- `php artisan cache:clear` 
- `csomposer dump-autoload`

Finally visit the in the browser http://localhost

## Media Types and data 

- Type sms
    - data = `['sms' => int]`
- Type email
    - data = `['email' => string]`
    
 Example of validation array:
 
```
return [
    'email' => 'email|required',
];
``` 

When adding new media types their key and expected values needs to be included in `Api\Validation\MediaValidationRule::class`  to secure that any data that is clean sanitaized before it is recorded to the registry. 

This application rules follows laravel validation [rules](https://laravel.com/docs/5.4/validation#available-validation-rules). For example to extend this to support external email clients:

```
'imap' => [
    'server' => 'string|required', // or any other custom validation
    'key' => 'string|required'
    ....
];
```
This will allow those media types to be saved this media type into any contact.