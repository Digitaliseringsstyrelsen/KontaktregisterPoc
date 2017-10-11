## Documentation
Basic documentation can be found at:
`http://localhost/api/documentation` using the following url `http://localhost/api-docs.json` to load the right json configuration.

## End points
```
+----------------------------------------+------------------------------------------------------------------+
| Method                                 | URI                                                              |
+----------------------------------------+------------------------------------------------------------------+
| GET|HEAD                               | /                                                                |
| POST                                   | api/contacts                                                     |
| GET|HEAD                               | api/contacts/{contact}                                           |
| GET|HEAD                               | api/contacts/{contact}/log                                       |
| POST                                   | api/contacts/{contact}/media-subscriptions                       |
| DELETE                                 | api/contacts/{contact}/media-subscriptions                       |
| GET|HEAD                               | api/contacts/{contact}/medias                                    |
| POST                                   | api/contacts/{contact}/medias                                    |
| DELETE                                 | api/contacts/{contact}/medias/{media}                            |
| PUT|PATCH                              | api/contacts/{contact}/medias/{media}                            |
| POST                                   | api/contacts/{contact}/subscriptions                             |
| GET|HEAD                               | api/contacts/{contact}/subscriptions                             |
| GET|HEAD                               | api/contacts/{contact}/subscriptions/{subscription}              |
| DELETE                                 | api/contacts/{contact}/subscriptions/{subscription}              |
| PUT|PATCH                              | api/contacts/{contact}/subscriptions/{subscription}              |
| POST                                   | api/contacts/{contact}/subscriptions/{subscription}/accept-terms |
| POST                                   | api/register                                                     |
| GET|HEAD                               | api/search                                                       |
| GET|HEAD                               | api/subscription-types                                           |
| GET|HEAD                               | api/terms                                                        |
| GET|HEAD                               | api/terms/{term}                                                 |
+--------+----------------------------------------+---------------------------------------------------------+
```
