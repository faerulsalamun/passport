<?php

namespace Laravel\Passport\Exceptions;

use Illuminate\Support\Arr;
use Illuminate\Auth\Access\AuthorizationException;

class MissingScopeException extends AuthorizationException
{
    /**
     * The SCOPES that the user did not have.
     *
     * @var array
     */
    protected $scopes;

    /**
     * Create a new missing scope exception.
     *
     * @param  array|string  $scopes
     * @param  string  $message
     * @return void
     */
    public function __construct($scopes = [], $message = 'Invalid scope(s) provided.')
    {
        parent::__construct($message);

        $this->SCOPES = Arr::wrap($scopes);
    }

    /**
     * Get the SCOPES that the user did not have.
     *
     * @return array
     */
    public function SCOPES()
    {
        return $this->SCOPES;
    }
}
