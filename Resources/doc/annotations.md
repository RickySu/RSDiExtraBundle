Annotations
===========

### @Inject

This marks a property, or parameter for injection:

```php
<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use RS\DiExtraBundle\Annotation\Inject;

class FooController extends Controller
{
    /**
     * @Inject("%foo%")  
     */
    private $foo;
    
    /**
     * @Inject("session")
     */
    private $session;
    
    /**
     * @Inject 
     */
    private $eventDispatcher;
    
}
```

#### Note
**Private property injection is controller only.**

If you don't define service explicitly, we will try to guess serivece id by property name.

for example $eventDispatcher => 'event_dispatcher'

### @InjectParams

This marks the parameters of a method for injection:

```php
<?php
namespace App\Service;

use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MyService
{
        
    /**
     * @InjectParams({
     *     "foo" = @Inject("%foo%")       
     * })
     */
    public function injectEventDispatcher($foo, EventDispatcherInterface $eventDispatcher, $session)
    {
        
    }
}
``` 

If you don't define service explicitly, we will try to guess serivece id by following steps.
1. TypeHint Autowiring
2. Name Convention, for example $eventDispatcher => 'event_dispatcher'


### @InjectParams

#### On classes

Marks a class as service.

```php
<?php
namespace App\Service;

use RS\DiExtraBundle\Annotation\Service;

/**
 * @Service("my_service", public=false, environments = {"prod", "dev"})
 */
class MyService
{
}
``` 

If you do not explicitly define a service id, we will use service full class name as id.

By default, service is public. 
 
#### On methods

Marks a method as a factory.

```php
<?php
namespace App\Service;

use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Annotation\Inject;

/**
 * @Service()
 */
class MyService
{
    protected $foo;
    protected $bar;
    
    /**
     * @InjectParams({
     *     "foo" = @Inject("%foo%"),
     *     "bar" = @Inject("%bar%"),       
     * })
     */
    public function __construct($foo, $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }
    
    /**
     * @Service("custom_service") 
     */
    public function createCustomService()
    {
        return new CustomService($this->foo, $this->bar);
    }
}
``` 

Define a service factory with parameters intection.

```php
<?php
namespace App\Service;

use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Annotation\Inject;

class MyService
{
    /**
     * @Service("custom_service") 
     * @InjectParams({
     *     "foo" = @Inject("%foo%"),
     *     "bar" = @Inject("%bar%"),       
     * })
     */
    public static function createCustomService($foo, $bar)
    {
        return new CustomService($foo, $bar);
    }
}
``` 

Use static factory with parameters.

#### Class extends or Trait use

```php
<?php
namespace App\Service;

use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Annotation\Inject;

abstract class BaseService
{
    protected $foo;

    /**
     * @InjectParams({
     *     "foo" = @Inject("%foo%"),       
     * })
     */
    public function injectFoo($foo)
    {
        $this->foo = $foo;
    }
    
}

trait BarInjectTrait
{
    protected $bar;
    
    /**
     * @InjectParams({
     *     "bar" = @Inject("%bar%"),       
     * })
     */
    public function injectBar($bar)
    {
        $this->bar = $bar;
    }
}

/**
 * @Service()
 */
class MyService extends BaseService
{
    use BarInjectTrait;
    
    /**
     * @Service("custom_service") 
     */
    public function createCustomService()
    {
        return new CustomService($this->foo, $this->bar);
    }
}
``` 


### @Tag

Add a tag to the service

```php
<?php

use RS\DiExtraBundle\Annotation\Tag;
use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Annotation\Inject;

/**
 * @Service()
 * @Tag("doctrine.event_listener", attributes = {"event" = "postGenerateSchema", lazy=true})
 */
class MyService
{
}
```

### @Observe

Register a event lister.


```php
<?php

use RS\DiExtraBundle\Annotation\Observe;
use RS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @Service()
 */
class MyListener
{
    /**
     * @Observe("kernel.request", priority = 255)
     */
    public function onKernelRequest()
    {
        
    }
    
    /**
     * @Observe(KernelEvents::REQUEST)
     */    
    public function onKernelRequest2()
    {
        
    }
}
```

You can use a string or conatant as event name.


### @Validator

Register a constraint validator.

```php
<?php
namespace App\Validator;

use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Annotation\Validator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class Foo extends Constraint
{
    public $message = 'This value should must be %value%';
    
    public function validatedBy()
    {
        return 'foo';
    }
}

/**
 * @Validator("foo")
 */
class FooValidator extends ConstraintValidator
{
    protected $foo;

    /**
     * @InjectParams({
     *     "foo" = @Inject("%foo%")
     * })
     */
    public function inject($foo)
    {
        $this->foo = $foo;
    }

    public function validate($value, Constraint $constraint)
    {
        if($value != $this->foo){
            $this->context->buildViolation($constraint->message)
                ->setParameter('%value%', $this->foo)
                ->addViolation();
        }
    }
}
```

#### @Validator implies @Service if you do not explicitly defined.


### @FormType

Register a custom form type

```php
<?php
namespace App\Form;

use RS\DiExtraBundle\Annotation\FormType;
use Symfony\Component\Form\AbstractType;


/**
 * @FormType
 */
class MyFormType extends AbstractType
{
    // ...
}


// Controller
$form = $this->createForm(MyFormType::class);
```

#### @FormType implies @Service if you do not explicitly defined.

### @DoctrineRepository

Register a Doctrine Repositoty

```php
<?php
namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use RS\DiExtraBundle\Annotation\DoctrineRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Annotation\InjectParams;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * 
 * @DoctrineRepository()
 */
class ProductRepository extends ServiceEntityRepository implements ContainerAwareInterface
{
    protected $container;
    
    /**
     * @InjectParams({
     *     "foo" = @Inject("%foo%")       
     * })
     */
    public function injectEventDispatcher($foo, EventDispatcherInterface $eventDispatcher, $session)
    {
        
    }
    
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}

//get repository
$repository = $container->get('doctrine')->getRepository(Product::class);
```

#### @DoctrineRepository implies @Service if you do not explicitly defined.

Automatically inject container if you implements ContainerAwareInterface.
Or use @InjectParams explicitly.


### @DoctrineListener or @DoctrineMongoDBListener

Register a Doctrine ORM or Doctrine MongoDB ODM listener

```php
<?php
namespace App\Listener;

use RS\DiExtraBundle\Annotation\DoctrineListener;

/**
 * @DoctrineListener(
 *     events = {"prePersist", "preUpdate"},
 *     connection = "default",
 *     lazy = true,
 *     priority = 0,
 * )
 */
class MyListener
{
    
}
```

#### @DoctrineListener or @DoctrineMongoDBListener implies @Service if you do not explicitly defined.


### Custom Annotations

An easy way to create custom annotation.

```php
<?php
namespace App\Annotation;

use RS\DiExtraBundle\Annotation\ClassProcessorInterface;
use RS\DiExtraBundle\Annotation\MethodProcessorInterface;
use RS\DiExtraBundle\Annotation\PropertyProcessorInterface;
use RS\DiExtraBundle\Converter\ClassMeta;

/**
 * @Annotation
 * @Target({"CLASS", "METHOD", "PROPERTY"})
 */
class MyTwig implements ClassProcessorInterface, MethodProcessorInterface, PropertyProcessorInterface
{
    public function handleClass(ClassMeta $classMeta, \ReflectionClass $reflectionClass)
    {
        //add define to $classMeta
        //register twig extension
        $classMeta->tags[] = 'twig_extension';
        $classMeta->public = false;
    }

    public function handleMethod(ClassMeta $classMeta, \ReflectionMethod $reflectionMethod)
    {
        //add define to $classMeta
    }

    public function handleProperty(ClassMeta $classMeta, \ReflectionProperty $reflectionProperty)
    {
        //add define to $classMeta
    }

}

/**
 * @MyTwig
 */
class MyTwigExtension
{
    
}
```
