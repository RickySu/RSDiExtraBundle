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


