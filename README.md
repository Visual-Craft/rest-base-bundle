RestBase Bundle
===============

Symfony Bundle which provides base foundation for REST API applications. Features include:
* Exceptions and errors converter to response
* RESTful decoding of HTTP request body and Accept headers

Installation
------------

### Step 1: Install the bundle

    $ composer require visual-craft/rest-base-bundle

### Step 2: Enable the bundle
If you are not using Flex, you also have to enable the bundle by adding the following line in the app/AppKernel.php:

```php
<?php
// AppKernel.php

// ...
use VisualCraft\RestBaseBundle\VisualCraftRestBaseBundle;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            // ...
            new VisualCraftRestBaseBundle(),
            // ...
        ];
    }
}
```
Errors
-----
### Configuration
Using the zone configuration, you can specify part of application where error converting enabled. 

Example:
```yaml
#config/packages/rest-base.php
visual_craft_rest_base:
    zone:
        path: '^/api/'
        host: null
        methods: []
        ips: []
```

### Supported exceptions
Supported by default exceptions list:
#### Symfony\Component\Security\Core\Exception\AuthenticationException

All authentication exceptions.

Response body:
```json
{
  "title": "Authentication error: An authentication exception occurred.",
  "statusCode": 401,
  "type": "authentication_error", 
  "details": []
}
```
----
#### Symfony\Component\HttpKernel\Exception\HttpExceptionInterface

HTTP error exceptions.

Response body:
```json
{
  "title": "HTTP error: Not Found",
  "statusCode": 404,
  "type": "http_error", 
  "details": []
}
```
----
#### VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters\InsufficientAuthenticationException
 
Thrown if the user credentials are not sufficiently trusted.
This is the case when a user is anonymous and the resource to be displayed has an access role.

Response body:
```json
{
  "title": "Insufficient authentication error: Not privileged to request the resource.",
  "statusCode": 401,
  "type": "insufficient_authentication_error", 
  "details": []
}
```
----
#### VisualCraft\RestBaseBundle\Exceptions\InvalidRequestException

Base exception thrown if request body are invalid.

Response body:
```json
{
  "title": "Invalid request",
  "statusCode": 400,
  "type": "invalid_request", 
  "details": []
}
```
----
#### VisualCraft\RestBaseBundle\Exceptions\InvalidRequestBodyFormatException
    
Extends from InvalidRequestException.
Thrown when symfony/serializer can't deserialize request body.

Response body:
```json
{
  "title": "Invalid request body format",
  "statusCode": 400,
  "type": "invalid_request_body_format",
  "details": {
    "cause": "unexpected_value"
  }
}
```
"cause" field values:
* "unexpected_value" if data fields have invalid values
* "extra_attributes" if request body have extra attributes
----
#### VisualCraft\RestBaseBundle\Exceptions\InvalidRequestContentTypeException

Extends from InvalidRequestException.
Thrown when no content type parameter are not pointed or content type have unsupported value.

Response body:
```json
{
  "title": "Invalid request content type",
  "statusCode": 400,
  "type": "invalid_request_content_type",
  "details": {
    "code": "unsupported",
    "valid_content_types": ["aplication/json'"]
  }
}
```

"code" field values:
* "missing" : if content type are not pointed
* "unsupported" : if content have unsupported value
----
#### VisualCraft\RestBaseBundle\Exceptions\ValidationErrorException

response body:
```json
{
  "title": "Validation error",
  "statusCode": 400,
  "type": "validation_error",
  "details": {
    "violations": {serialized by symfony/serializer ConstraintViolationList}
  }
}
```
----
### Enable support security exceptions
If you use separate firewall for your API, use `VisualCraft\RestBaseBundle\Security\AuthenticationEntryPoint`
```yaml
#config/packages/security.php
security:
    firewalls:
        main:
            entry_point: 'VisualCraft\RestBaseBundle\Security\AuthenticationEntryPoint'
            //..
```
If you want to use your custom entry point class, please edit your class next way:
``` php
<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use VisualCraft\RestBaseBundle\Constants;

class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        if ($request->attributes->get(Constants::API_ZONE_ATTRIBUTE)) {
            if ($authException) {
                throw $authException;
            }

            throw new AuthenticationException('Authentication required');
        }
        // Not API zone handle
    }
}
```
### Support custom exception
You can create and add your own exceptions and convertors for them.

* Create your exception
    ```php
    <?php
    
    declare(strict_types=1);
    
    namespace App\Exceptions;
    
    use Throwable;
    
    class CustomException extends \RuntimeException
    {
        private string $customField;
        
        public function __construct(string $customField, $message = "",$code = 0,Throwable $previous = null)
        {
            parent::__construct($message,$code,$previous);
            $this->customField = $customField;
        }
        
        public function getCustomField(): string
        {
            return $this->customField;
        }
    }
    ```
* Create converter
    ```php
    <?php
    //src/Problem/ExceptionToProblemConverters/InvalidRequestBodyFormatExceptionConverter.php
    
    declare(strict_types=1);
    
    namespace VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters;
    
    use Symfony\Component\HttpFoundation\Response;
    use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
    use VisualCraft\RestBaseBundle\Problem\Problem;
    
    class CustomExceptionConverter implements ExceptionToProblemConverterInterface
    {
        public function convert(\Throwable $exception): ?Problem
        {
            if (!$exception instanceof CustomException) {
                return null;
            }
    
            $result = new Problem(
                'Custom exception title',
                Response::HTTP_BAD_REQUEST,
                'custom_exception_type'
            );
            $result->addDetails('cause', 'custom exception cause');
    
            return $result;
        }
    }
    ```
* Register your class as a service and tag it with `visual_craft.rest_base.exception_to_problem_converter`. 
If you're using autoconfiguration, Symfony will automatically add this tag.

* Throw exception
    ```php
    <?php
    //src/Controller
    
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    
    class ThrowInvalidRequestExceptionController extends AbstractController
    {
        public function __invoke(Request $request): void
        {
            //..
            throw new CustomException();
            //..
        }
    }
    ```

* Response body
    ```json
    {
        "title" : "Custom exception title",
        "statusCode" : 400,
        "type" : "custom_exception_type",
        "details" : {
            "cause" : "custom exception cause"
        }
    }
    ```

### Request Body Deserializer
Api Body Deserializer contains:
* detect and check deserialization format
* deserialize using symfony/serializer and handle exceptions
* validate using symfony/validator with violations converting

Example:
```php
<?php
// src/Controller/ProcessRequestController.php

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use VisualCraft\RestBaseBundle\Request\RequestBodyDeserializer;

class ProcessRequestController extends AbstractController
{
    private RequestBodyDeserializer $deserializer;

    public function __construct(RequestBodyDeserializer $deserializer)
    {
        $this->deserializer = $deserializer;
    }

    public function __invoke(Request $request): Response
    {
        //..
        $testDto = $this->deserializer->deserialize($request, Dto::class);
        //..
    }
}
```
####Content type configuration
```yaml
#config/packages/rest-base.php
visual_craft_rest_base:
    mimeTypes:
        json: 'application/json'
        //..
```

### Debug
To enable exception stack trace in error response body needed to change config:
```yaml
#config/packages/rest-base.php
visual_craft_rest_base:
    debug: true
```
Error response stack trace example:
```json
{
    ....
    "details": {
        "class": "Namespace\\ExceptionClass",
        "message": "Exception message",
        "stack_trace": "Stack trace as a string"
    }
    
}
```


### Failing Validator
Rest base bundle also contained FailingValidator class which can help you validate your data objects with converting violations :
```php
<?php
// src/Controller/ProcessRequestController.php

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use VisualCraft\RestBaseBundle\Request\RequestBodyDeserializer;

class ProcessRequestController extends AbstractController
{
    private FailingValidator $validator;

    public function __construct(
        FailingValidator $validator
    ) {
        $this->validator = $validator;
    }

    public function __invoke(Request $request): Response
    {
        //..
        $this->validator->validate($data);
        //..
    }
}
```

Tests
-----
```sh
$ vendor/bin/simple-phpunit install
$ vendor/bin/phpunit
```

Additional Tools
-----
```sh
$ composer install
$ vendor/bin/php-cs-fixer fix
$ vendor/bin/psalm
```

License
-------

This bundle is released under the MIT license. See the complete license in the file: `LICENSE`
