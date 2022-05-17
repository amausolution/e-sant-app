<?php
#Feggu/Core/Front/Models/PartnerConsultation.php
namespace Feggu\Core\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartnerConsultation extends Model
{
    protected $guarded    = [];
    public $table = AU_DB_PREFIX.'consultation';
    protected $connection = AU_CONNECTION;

    use SoftDeletes;

    public function hospital()
    {
        return $this->belongsTo(FegguPartner::class,'hospital_id','id');
    }
    public function patient()
    {
        return $this->belongsTo(FegguUser::class,'patient_id','id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class,'department_id','id');
    }
    public function doctor()
    {
        return $this->belongsTo(Department::class,'doctor_id','id');
    }
    /*
Full name
 */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function printTicket($data)
    {
        $title = 'Print Ticket';
        return view('vendor.feggu-partner.invoice.print_ticket', compact('title', 'data'));
    }

    public function scopeOrderByName($query)
    {
        $query->orderBy('created_at')->orderBy('ticket');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('ticket', '=', '%'.$search.'%')
                    ->orWhereHas('patient', function ($query) use ($search) {
                        $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('last_name', 'like', '%'.$search.'%')
                            ->orWhere('first_name', 'like', '%'.$search.'%');
                    });
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
