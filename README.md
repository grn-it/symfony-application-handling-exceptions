# Symfony Application Handling Exceptions

An sample application showing how to handle uncaught exceptions.  
Read [Handling Exceptions in a Symfony Application](https://web-mastering.blogspot.com/2022/08/handling-exceptions-in-symfony.html) to learn more about how exceptions are handled by the Symfony kernel.

All uncaught exceptions are handled by the Symfony kernel and return data created by SerializerErrorRenderer (via ProblemNormalizer), either TwigErrorRenderer or HtmlErrorRenderer.  

In this sample application, an ExceptionNormalizer has been added to control response data requested in JSON format and Twig templates to control response data requested in HTML format.  

# Install
```bash
docker-compose exec symfony-web-application make install uid=$(id -u)
```

### Additional normalizer controlling response data for dev and prod environments

```php
// src/Serializer/Normalizer/ExceptionNormalizer.php

class ExceptionNormalizer implements NormalizerInterface
{
    public function __construct(#[Autowire('%kernel.debug%')] private readonly bool $debug = false) {}

    /**
     * @param FlattenException $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        if (!$object instanceof FlattenException) {
            throw new InvalidArgumentException(sprintf('The object must implement "%s".', FlattenException::class));
        }
        
        // in all environments for domain exceptions only a message will be shown
        if ($object->getClass() === DomainException::class) {
            return ['message' => $object->getMessage()];
        }
        
        // in dev and test environment all data of the exception will be shown
        if ($this->debug) {
            return $object->toArray();
        }

        // in prod environment no data will be shown
        return [];
    }
    
    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof FlattenException;
    }
}
```

### Twig template returning data in HTML format
```html
<!-- templates/bundles/TwigBundle/Exception/error500.html.twig -->
<html>
    <body>
        <h1>500 Error Page</h1>

        <p>Status code: {{ status_code }}</p>
        <p>Status text: {{ status_text }}</p>
    </body>
</html>
```

## Resources
[How to Customize Error Pages](https://symfony.com/doc/current/controller/error_pages.html)  
[Handling Exceptions in a Symfony Application](https://web-mastering.blogspot.com/2022/08/handling-exceptions-in-symfony.html)
