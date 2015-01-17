<?php namespace Decorators;

interface DataDecoratorInterface
{
    /**
    * Get all of the data.
     *
     * @return array
     */
    public function all();

    /**
     * Determine if the data contains a non-empty value for an specified item.
     *
     * @param  string|array  $key
     * @return bool
     */
    public function has($key);

    /**
     * Get an item from the data.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Get a subset of the items from the data.
     *
     * @param  array  $keys
     * @return array
     */
    public function only($keys);

    /**
     * Get all of the data except for a specified array of items.
     *
     * @param  array  $keys
     * @return array
     */
    public function except($keys);

    /**
     * Merge new data into the current data array.
     *
     * @param  array  $data
     * @return void
     */
    public function merge(array $input);
}
