<?php

namespace li3_action_filter;

use lithium\action\Dispatcher;

class Filters extends \lithium\util\collection\Filters {
    
    public static function apply($class, $method, $filter) {
        Dispatcher::applyFilter('_call', function($self, $params, $chain) use ($class, $method, $filter) {
            $controller = is_subclass_of($params['callable'], 'lithium\action\Controller') ? $params['callable'] : null;
            if (is_object($controller) && get_class($controller) == $class) {
                $controller->applyFilter($method, $filter);
            }
            return $chain->next($self, $params, $chain);
        });
    }
}

?>