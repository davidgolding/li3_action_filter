li3_action_filter
=================

Lithium plugin for adding filters to controller actions.

Lithium's filtering mechanism does not directly support applying filters to controller actions. This plugin enables action-level filtering by creating applying filter calls on the Dispatcher. The method for applying filters on controller actions follows the same patterns as other filters in the native Li3 stack.

###Installation

Installing and using `li3_action_filter` is simple. Simply install the `li3_action_filter` contents in your application's `app\libraries` directory, then add the plugin in the `libraries.php` bootstrap file. Then, wherever you need to apply a filter on a controller action, invoke the `li3_action_filter\Filters` class.

_In the `app/config/bootstrap/libraries.php` file:_

```php
<?php

//app/config/bootstrap/libraries.php
// ...

Libraries::add('li3_action_filter', ['bootstrap' => false]);

// ... other libraries

?>
```

_Anywhere else in the app:_

```php
<?php

use li3_action_filter\Filters;
use lithium\analysis\Logger;

Filters::apply('lithium\action\Controller', 'view', function($self, $params, $chain) {
    Logger::write('debug', print_r($params, true));
    return $chain->next($self, $params, $chain);
});

```

###Usage

For filters to work, you must make a controller action filterable first. This is done as you would expect for creating any other kind of typical Li3 filter. This plugin utilizes the built-in Controller class's `_filter` method, so simply run this on an action where you wish to allow filtering.

```php
<?php

namespace app\controllers;

class PagesController extends \lithium\action\Controller {
    
    public function view() {
        $this->_render['template'] = $this->request->page;
        return $this->_filter(__METHOD__, $this->request->params, function($self, $params) {
            return $self->render($params);
        });
    }
}

?>

```

With the `PagesController::view` action filterable, we can now apply a filter anywhere else in the app, like so:

```php
<?php

//app/config/bootstrap/action.php

// ...

use li3_action_filter\Filters;

Filters::apply('app\controllers\PagesController', 'view', function($self, $params, $chain) {
    //we can perform any logic we want
    return $chain->next($self, $params, $chain);
});

```