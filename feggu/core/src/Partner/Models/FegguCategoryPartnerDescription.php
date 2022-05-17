<?php

namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FegguCategoryPartnerDescription extends Model
{
    protected $primaryKey = ['category_id', 'lang'];
    public $incrementing  = false;
    public $timestamps    = false;
    public $table = AU_DB_PREFIX.'category_partner_description';
    protected $connection = AU_CONNECTION;
    protected $guarded    = [];
}
