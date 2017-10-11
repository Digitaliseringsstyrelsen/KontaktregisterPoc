## List of help commands
### New installation instance
When installing a new container, some permissions needs to be changed in order to make the application
run in a proper way:

```bash
    chown -R www-data storage; 
    chown -R www-data bootstrap/cache; 
    chmod -R 0770 storage; 
    composer install; 
    php artisan cache:clear; 
    php artisan optimize;
```

### Port issues
