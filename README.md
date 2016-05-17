# Event Registry and Handling

This is part of a two-part blog post, looking at writing re-usable components and then packaging them up via Composer.

Although the source code in this repository works, and it is a pretty usable Event Handling system (with a semi-decent API) - it's probably best not to use this in production whereby there are far better options.

That said, if you want an incredibly lightweight system for registering and handling events, and don't particularly mind about pesky things such as tests (..!) then this will most likely suffice.


## Blog Post

Not written yet, I thought I'd do the fun part first. ;)

### Example:

    <?php
    use FergusInLondon\Events\Registry;
    use FergusInLondon\Events\Handler;

    $registry = new Registry();
    $registry->registerHandler("event.demo", new Handler(function(){
	    echo "See, this is a very simple event handler.\n";
    }));
    $registry->registerHandler("event.never", new Handler(function(){
	    echo "This will never run, as we'll clear all handlers first.\n";
    }));
    
    $userCreateHandler = new Handler(function($name, $id){
		printf("User created: %s (%d)\n", $name, $id);
    });

    $userDeleteHandler = new Handler(function($name, $id){
        printf("User created: %s (%d)\n", $name, $id);
    });

    $registry->registerHandler("user.create", $userCreateHandler);
    $registry->registerHandler("user.delete", $userDeleteHandler);


	$registry->trigger("event.demo");
	$registry->clearHandlers("event.demo");
	$registry->trigger("event.demo");
    
    $user = ["name" => "J. Smith", "id" => 101];
    $registry->trigger("user.create", $user);
    $registry->trigger("user.delete", $user);

    $registry->clearHandlers();
    $registry->trigger("event.never");