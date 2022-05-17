<?php

namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerLog extends Model
{
    protected $guarded = [];
    public $table = AU_DB_PREFIX.'partner_log';
    public static $methodColors = [
        'GET' => 'green',
        'POST' => 'yellow',
        'PUT' => 'blue',
        'DELETE' => 'red',
    ];

    public static $methods = [
        'GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH',
        'LINK', 'UNLINK', 'COPY', 'HEAD', 'PURGE',
    ];

    /**
     * Log belongs to users.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(PartnerUser::class);
    }
}
