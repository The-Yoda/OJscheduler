<?php
trait Prototype {
    private static $_methods = [];

    function __set( $name, $value ) {
        if( $value instanceof \Closure ) {
            self::$_methods[$name] = $value;
        }
    }

    function __call( $name, $args = array() ) {
        if (!empty(self::$_methods[$name])) {
            $method = self::$_methods[$name];
            if(!empty($method) && $method instanceof \Closure ) {
                $method = $method->bindTo( $this );
                return call_user_func_array( $method, $args );
            }
        }
    }
}
?>