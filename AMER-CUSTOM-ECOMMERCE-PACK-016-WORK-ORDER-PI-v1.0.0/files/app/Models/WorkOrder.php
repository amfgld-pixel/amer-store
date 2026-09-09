<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class WorkOrder extends Model
{
    protected $fillable=['number','order_id','status','priority','issue_date','required_date','assigned_to','instructions','created_by','released_by','released_at','completed_at'];
    protected function casts():array{return ['issue_date'=>'date','required_date'=>'date','released_at'=>'datetime','completed_at'=>'datetime'];}
    public function order():BelongsTo{return $this->belongsTo(Order::class);}
    public function items():HasMany{return $this->hasMany(WorkOrderItem::class)->orderBy('line_order');}
}

