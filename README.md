# Routing

Extendable Router that uses Attributes to define routes to methods

## Installing

You can install this library with composer by running following command

```bash
composer require meowphp/routing
```

## Usage

### Registering controllers

This router need to have statically registered controllers in array. To do that
add somewhere in your app array with controller classes strings:

```php
/**
 * Register your application's controller here
*/
$controllers => [
    \May\AttributesTest\MainController::class,
    \May\AttributesTest\Controllers\ExampleController::class
],
```

### Defining routes

To define routes to the action use `Route` attribute for your methods:

```php
# Route without attributes
#[Route('/hello/{id}/{surname}')]
public function sayHello() : string

# Route with attributes
#[Route("/good-bye")]
public function sayGoodBye() : string

# Default route
#[Route('/')]
public function index() : string
```

### Getting new Router instance

To get new router use script as follows.

```php
$router = \Meow\Routing\Router::getRouter($controllers);
```

### Resolving routes

```php
$calledRoute = $this->router->matchFromUri('/your/route');
```

### Getting controller and action

Code above will return instance of route so to get controller and action use
functions below

```php
$calledRoute->getController();
$calledRoute->getMethod();
```

### Getting parameters

```php
if ($calledRoute->hasParameters()) {
    $request = $calledRoute->getParameters();
}
```

### Prefixes

Prefixes can be applied on router class. After applying them router starts with prefix `/{prefix}/{action}`.
So instead of defining routes as `/users/action` and `/users/action-two` You can use Prefix and `/users` and 
then define route for actions as `/action` and `/action-two`. Example bellow:

```php
#[Prefix('/api')]
class ExampleController extends AppController
```

__License: MIT__
