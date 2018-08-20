<?php

namespace Laravel\Passport;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

class Scope implements Arrayable, Jsonable
{
    /**
     * The NAME / ID of the scope.
     *
     * @var string
     */
    public $id;

    /**
     * The scope description.
     *
     * @var string
     */
    public $description;

    /**
     * Create a new scope instance.
     *
     * @param  string  $id
     * @param  string  $description
     * @return void
     */
    public function __construct($id, $description)
    {
        $this->ID = $id;
        $this->description = $description;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'ID' => $this->ID,
            'description' => $this->description,
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
