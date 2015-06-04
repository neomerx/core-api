### Supported methods

|Method|End Point             |Description                |
|------|----------------------|---------------------------|
|GET   |/languages            | Get all languages.        |
|POST  |/languages            | Create new language.      |
|GET   |/languages/{iso_code} | Read language by iso code.|
|PATCH |/languages/{iso_code} | Update language.          |
|DELETE|/languages/{iso_code} | Delete language.          |

#### GET sample

Request
```
GET http://example.com/api/v1/languages

Content-Type: application/vnd.api+json
Accept: application/vnd.api+json
X-Requested-With: XMLHttpRequest
authToken: OGvrzRcHiPIgpWk6rlcTVmSHOBKqEi8im9Q8UmBlWLRonsHAXIV92Llb2aBv
```

Response

```json
{
  "data": [
    {
      "type": "languages",
      "id": "eng",
      "attributes": {
        "name": "English"
      },
      "links": {
        "self": "http://example.com/api/v1/languages/eng"
      }
    }
  ]
}
```