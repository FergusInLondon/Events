# Events: A simple events registry and handling system

[![Build Status](https://travis-ci.org/FergusInLondon/Events.svg?branch=master)](https://travis-ci.org/FergusInLondon/Events)  [![Code Climate](https://codeclimate.com/github/FergusInLondon/Events/badges/gpa.svg)](https://codeclimate.com/github/FergusInLondon/Events) [![Issue Count](https://codeclimate.com/github/FergusInLondon/Events/badges/issue_count.svg)](https://codeclimate.com/github/FergusInLondon/Events)

This is an incredibly simple events registry and handling system, which is composed of a total of two objects: a registry object, and a subclassable base handler.

The primary intention behind this project was for a three part blog post about writing small modular components, packaging them as composer modules and subsequently using a continuous integration system to ensure their status.

Although that was the original intention, if you need an incredibly lightweight system for registering and handling events, then this will most likely suffice!

### Blog Post(s)

The blog posts are not yet written, but will be made available on [Fergus.London](https://fergus.london).

## Example:

The best form of documentation is usually an example.

### Basic
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

### Full API

Alternatively, the example below demonstrates the entirety of the API and feature-set. There are public methods open on these objects which *should not be used*: and are there for `registry<->handler` communications. These methods will be marked as such in the source code.


```php
use FergusInLondon\Events\Registry;
use FergusInLondon\Events\Handler;

$registry = new Registry();

// As Handler objects are - by default - initialised with a Callable parameter. This is overridable via subclassing.
$registry->registerHandler("event.demo", new Handler(function(){
    echo "See, this is a very simple event handler.\n";
}));
$registry->registerHandler("event.never", new Handler(function(){
    echo "This will never run, as we'll clear all handlers first.\n";
}));

// Handlers can be instantiated inline, and access parameters passed in via Registry::trigger()
$userDeleteHandler = new Handler(function($name, $id){
    printf("User created: %s (%d)\n", $name, $id);
});

// Handlers also gain the context of the Handler object. Especially useful if you need to subclass and/or access utility methods.
$userCreateHandler = new Handler(function($name, $id){
	printf("User created: %s (%d)\n", $name, $id);
	printf(
	    "Handlers have access to the current Handler object too. (i.e %s)",
	    $this->registryIdentifier
	);
});


// Registering instantiated handlers.
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

## Testing

Testing is done via PHPSpec, and tests that the public interface works as expected - but makes no guarantees as to the internal workings. As such, if you wish to use this project please stick to the public API as seen in the above example(s).

A [full build history](https://travis-ci.org/FergusInLondon/Events/builds) for this project is available on Travis CI.

## License

All code contained in this repository is licensed under **[The MIT License](https://opensource.org/licenses/MIT)**.

> Copyright © 2016 Fergus Morrow <fergus@fergus.london>
> 
> Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the “Software”), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:
> 
> The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.
> 
> THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
