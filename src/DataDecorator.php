<?php namespace Decorators;

abstract class DataDecorator implements DataDecoratorInterface
{
    protected $data = [];

    /**
    * Get all of the data.
     *
     * @return array
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * Determine if the data contains a non-empty value for an specified item.
     *
     * @param  string|array  $key
     * @return bool
     */
    public function has($key)
    {
        $keys = is_array($key) ? $key : func_get_args();

        foreach ($keys as $value) {
            if ($this->isEmpty($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get an item from the data.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return array_get($this->data, $key, $default);
    }

    /**
     * Get a subset of the items from the data.
     *
     * @param  array  $keys
     * @return array
     */
    public function only($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        $results = [];

        foreach ($keys as $key) {
            array_set($results, $key, array_get($this->data, $key));
        }

        return $results;
    }

    /**
     * Get all of the data except for a specified array of items.
     *
     * @param  array  $keys
     * @return array
     */
    public function except($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        $results = $this->all();

        array_forget($results, $keys);

        return $results;
    }

    /**
     * Merge new data into the current data array.
     *
     * @param  array  $data
     * @return void
     */
    public function merge(array $data)
    {
        $this->data = array_replace($this->data, $data);
    }

    /**
     * Determine if the data at a given key is an empty string for "has()".
     *
     * @param  string  $key
     * @return bool
     */
    protected function isEmpty($key)
    {
        $value = $this->get($key);

        if (is_null($value)) {
            return true;
        }

        if (is_string($value)) {
            $boolOrArray = is_bool($value) || is_array($this->get($key));

            return !$boolOrArray && trim((string) $this->get($key)) === '';
        }

        return false;
    }
}
