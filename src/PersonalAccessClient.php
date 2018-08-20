<?php

namespace Laravel\Passport;

use Illuminate\Database\Eloquent\Model;

class PersonalAccessClient extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'OAUTH_PERSONAL_ACCESS_CLIENTS';

    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get all of the authentication codes for the client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Passport::clientModel());
    }
}
