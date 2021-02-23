<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class Ticket extends Model
{
    protected $fillable = [
        'custom_id', 'frame_id', 'plate', 'brand', 'model', 'subject', 'description', 'tests_done', 'ask_for', 'knowledge_base',
        'engine_type',
        // FOREIGN KEYS
        'customer_id', 'department_id', 'user_id', 'deleted_by', 'call_id', 'ticket_status_id'
    ];

    protected $casts = [
        'knowledge_base' => 'boolean',
    ];

    protected $with = [
        'comments', 'status', 'calls', 'attachments', 'comment_attachments'
    ];

    protected $appends = ['subject_short'];

    public function getSubjectShortAttribute() {
        return Str::substr($this->attributes['subject'], 0, 25);
    }
    public function department()
    {
        return $this->belongsTo(\App\Models\Department::class, 'department_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function deleted_by()
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by', 'id');
    }

    public function attachments()
    {
        return $this->morphToMany(\App\Models\Attachment::class, 'attachable');
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class, 'ticket_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(\App\Models\TicketStatus::class, 'ticket_status_id', 'id');
    }

    public function calls()
    {
        return $this->hasMany(\App\Models\Call::class, 'ticket_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(\App\Models\Brand::class, 'brand_id', 'id');
    }

    public function car_model()
    {
        return $this->belongsTo(\App\Models\CarModel::class, 'car_model_id', 'id');
    }

    /**
     * Get all of the comment_attachments for the Comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function comment_attachments(): HasManyThrough
    {
        return $this->hasManyThrough(Attachable::class, Comment::class, 'ticket_id', 'attachable_id', 'id', 'id')
            ->where('attachable_type', 'App\Models\Comment');
    }

    public static function getTickets(Request $req, $type = 'tickets') {

        return Ticket::when($type == 'faqs', function(Builder $q, $faqs){
            $q->where('knowledge_base', 1);
        })->when($req->ticket_id, function(Builder $q, $id) {
            $q->where('custom_id', 'LIKE', '%' . $id . '%');
        })->when($req->frame_id, function(Builder $q, $frame_id) {
            $q->where('frame_id', 'LIKE', $frame_id . '%');
        })->when($req->plate, function(Builder $q, $plate) {
            $q->where('plate', 'LIKE', $plate . '%');
        })->when($req->brand, function(Builder $q, $brand) {
            $q->where('brand', 'LIKE', $brand . '%');
        })->when($req->model, function(Builder $q, $model) {
            $q->where('model', 'LIKE', $model . '%');
        })->when($req->engine_type, function(Builder $q, $engine_type) {
            $q->where('engine_type', 'LIKE', $engine_type . '%');
        })->when($req->subject, function(Builder $q, $subject) {
            $q->where('subject', 'LIKE', $subject . '%');
        })->when($req->description, function(Builder $q, $description) {
            $q->where('description', 'LIKE', $description . '%');
        })->when($req->tests_done, function(Builder $q, $tests_done) {
            $q->where('tests_done', 'LIKE', $tests_done . '%');
        })->when($req->date_from, function(Builder $q, $date_from) {
            $q->whereDate('tickets.created_at', '>=', $date_from);
        })->when($req->date_to, function(Builder $q, $date_to) {
            $q->whereDate('tickets.created_at', '<=', $date_to);
        })->when($req->knowledge_base, function(Builder $q, $knowledge_base) {
            switch ($knowledge_base) {
                case '1':
                    $q->where('knowledge_base', 1);
                    break;
                
                default:
                    $q->where('knowledge_base', 0);
                break;
            }
        })->when($req->order_by, function(Builder $q, $order_by) {
            $q->orderBy($order_by->field, $order_by->value);
        },function ($q) {
            $q->orderBy('tickets.created_at', 'DESC');
        })
        // SI ESTE TICKET ESTADO BUSCA POR EL ID DEL ESTADO
        ->when($req->status, function(Builder $q, $status_id) {
            $q->whereHas('status', function($q2) use ($status_id){
                $q2->where('ticket_statuses.id', $status_id);
            });
        })
        // SI ESTE TICKET TIENE USUARIOS BUSCA POR EL NOMBRE
        ->when($req->user_name, function(Builder $q, $user_name){
            $q->whereHas('user', function($q2) use ($user_name){
                $q2->where('users.name', 'LIKE', $user_name . '%');
            });
        })
        // SI ESTE TICKET TIENE UN CLIENTE BUSCA POR EL ID
        ->when($req->customer_custom_id, function(Builder $q, $custom_id){
            $q->whereHas('customer', function($q2) use ($custom_id){
                $q2->where('customers.custom_id', 'LIKE', $custom_id . '%');
            });
        }, function(Builder $q){
            if(!auth()->user()->hasRole(['superadmin', 'admin', 'staff'])) {
                $q->whereHas('customer', function($q2) {
                    $q2->where('customers.id', auth()->user()->customer_id);
                });
            }
        })
        // SI ESTE TICKET TIENE UN CLIENTE BUSCA POR EL ID
        ->when($req->customer_name, function(Builder $q, $customer_name){
            $q->whereHas('customer', function($q2) use ($customer_name){
                $q2->where('customers.comercial_name', 'LIKE', $customer_name . '%')
                    ->orWhere('customers.fiscal_name', 'LIKE', $customer_name . '%');
            });
        })
        // SI ESTE TICKET TIENE UN DEPARTAMENTO BUSCA POR EL ID
        ->when($req->department_id, function(Builder $q, $department_id){
            $q->whereHas('department', function($q2) use ($department_id){
                $q2->where('departments.id', $department_id);
            });
        })
        ->with(['user', 'customer', 'department', 'status'])
        ->withCount(['comments', 'calls']);
    } 
}