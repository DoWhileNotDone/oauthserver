<?php declare(strict_types=1);

namespace OAuthServer\Models;

use Illuminate\Database\Eloquent\Model;

class Authorization extends Model
{
    protected $primaryKey = 'authorization_id';

    /**
     * Get the application associated with the authorization.
     */
    public function application()
    {
        return $this->belongsTo('OAuthServer\Models\Application', 'application_id');
    }
}
