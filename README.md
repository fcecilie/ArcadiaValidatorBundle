# ArcadiaValidatorBundle

Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require arcadia/validator-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require arcadia/validator-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Arcadia\Bundle\ValidatorBundle::class => ['all' => true],
];
```

Usage
=====

The `ValidationService` validate your model and give you a well formatted response if there are validation errors. 

```php
// src/Model/DocumentModel.php
<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class DocumentModel
{
    /**
     * @Assert\NotBlank(allowNull=true, groups={"create"})
     * @Assert\Type(type={"string", "null"}, groups={"create"})
     * @Assert\Length(max=210, groups={"create"})
     */
    public $pathPrefix;

    /**
     * @Assert\NotBlank(groups={"create"})
     * @Assert\Type(type="string", groups={"create"})
     * @Assert\Length(max=255, groups={"create"})
     */
    public $originalName;
}
```

```php
// src/Controller/DocumentController.php

public function create(Request $request, SerializerInterface $serializer, ValidatorService $validatorService): Response
{
    $documentModel = $serializer->deserialize($request->getContent(), DocumentModel::class, 'json');

    // Validation
    $response = $validatorService->getFailedValidationResponse($documentModel, null, ['create']);
    if ($response !== null) {
        return $response;
    }
    
    // DocumentModel is valid ...
}
```

Exemple of validation error response : 
```json
{
  "status": 400,
  "message": "Validation failed",
  "errors": {
    "pathPrefix": [
      "This value should not be blank."
    ]
  }
}
```