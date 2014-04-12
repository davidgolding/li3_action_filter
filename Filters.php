<?php
/**
 * li3_action_filter
 *
 * Lithium plugin for adding filters to controller actions.
 * @copyright   (c) 2014 David Golding
 * @license     http://opensource.org/licenses/MIT
 */
namespace li3_action_filter;

use lithium\action\Dispatcher;

/**
 * The Filters class applies action-level filters by going a level deeper
 * into the Dispatcher's `_call` method. It can be (auto)loaded anywhere in
 * the app by adding the `li3_action_filter` plugin to the `libraries.php`
 * bootstrap and calling `use li3_action_filter\Filters`.
 */
class Filters extends \lithium\util\collection\Filters {
    
    /**
     * Applies a closure function to a controller action filter. The action
     * must already be filterable by using `lithium\action\Controller->_filter()`.
     *
     * @param string $class     The fully namespaced controller
     * @param string $method    The name of the filterable action
     * @param closure $filter   A closure that adheres to Lithium's filtering protocol
     * @return void
     */
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