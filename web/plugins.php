<?php
class plugins {
    private static $hooks = [];

    public static function register($hook, $callback) {
        if (!isset(self::$hooks[$hook])) {
            self::$hooks[$hook] = [];
        }
        self::$hooks[$hook][] = $callback;
    }

    public static function call($hook, $params = []) {
        if (isset(self::$hooks[$hook])) {
            foreach (self::$hooks[$hook] as $callback) {
                call_user_func($callback, $params);
            }
        }
    }
}

// Example: Register a sample hook (can be extended)
plugins::register('cronCalled', function($params) {
    // Custom cron logic here
});
?>
