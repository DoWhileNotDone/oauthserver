<?php declare(strict_types=1);

namespace OAuthServer\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $primaryKey = 'application_id';

    /**
     * Get the authorizations associated with the application.
     */
    public function authorizations()
    {
        return $this->hasMany('OAuthServer\Models\Authorization');
    }
}
