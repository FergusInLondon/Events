# Event Registry and Handling

This is part of a two-part blog post, looking at writing re-usable components and then packaging them up via Composer.

Although the source code in this repository works, and it is a pretty usable Event Handling system (with a semi-decent API) - it's probably best not to use this in production whereby there are far better options.

That said, if you want an incredibly lightweight system for registering and handling events, and don't particularly mind about pesky things such as tests (..!) then this will most likely suffice.


## Blog Post

Not written yet, I thought I'd do the fun part first. ;)

### Example:

#### Basic
A basic example could be a User registration/creation event, whereby when a user signs up to your application an event is fired that allows other parts of the application to respond in some way.



```php
use FergusInLondon\Events\Registry;
use FergusInLondon\Events\Handler;

$registry = new Registry();

$registry->registerHandler("user.creation", new Handler(function($user){
	$this->container->get('email')->sendWelcome( $user->email_address );
}));

$registry->registerHandler("", new Handler(function($user){
    $this->container->get('logger')->info(
    	sprintf("New user registration: %s (%d)", $user->name, $user->id)
    );
}));
```

Meanwhile, in our associated controller we would simply trigger the event when required..

```php
class UserController {
	public function create() {
		// ...
		$user->save();
		$registry->trigger("user.creation", $user);
	
	}
}


```

#### Full API

```php
use FergusInLondon\Events\Registry;
use FergusInLondon\Events\Handler;

$registry = new Registry();

// As Handler objects are essentially 
$registry->registerHandler("event.demo", new Handler(function(){
    echo "See, this is a very simple event handler.\n";
}));
$registry->registerHandler("event.never", new Handler(function(){
    echo "This will never run, as we'll clear all handlers first.\n";
}));

//
$userCreateHandler = new Handler(function($name, $id){
	printf("User created: %s (%d)\n", $name, $id);
	printf(
	    "Handlers have access to the current Handler object too. (i.e %s)",
	    $this->registryIdentifier
	);
});

//
$userDeleteHandler = new Handler(function($name, $id){
    printf("User created: %s (%d)\n", $name, $id);
});

//
$registry->registerHandler("user.create", $userCreateHandler);
$registry->registerHandler("user.delete", $userDeleteHandler);

// The clearHandlers method is capable of
$registry->trigger("event.demo");
$registry->clearHandlers("event.demo");
$registry->trigger("event.demo");

// It's possible to trigger events whilst passing data to the handler
$user = ["name" => "J. Smith", "id" => 101];
$registry->trigger("user.create", $user);
$registry->trigger("user.delete", $user);

// If clearHandlers is called without any parameters, all handlers are cleared
$registry->clearHandlers();
$registry->trigger("event.never");
```