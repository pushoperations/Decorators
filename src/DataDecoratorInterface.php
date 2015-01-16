<?php namespace Decorators;

interface DataDecoratorInterface
{
    public function all();

    public function has($key);

    public function get($key, $default = null);

    public function only($keys);

    public function except($keys);

    public function merge(array $input);
}
