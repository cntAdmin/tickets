<?php

namespace App\Models;

use App\Scopes\RoleTicketFilterScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'custom_id', 'frame_id', 'plate', 'brand', 'model', 'subject', 'description', 'tests_done', 'ask_for_id', 'knowledge_base',
        'engine_type', 'other_brand_model', 'assigned_to_id', 'resolution_time',
        // IF TRUE => ADMIN HAS ANSWERED
        'answered',
        // FOREIGN KEYS
        'customer_id', 'department_id', 'user_id', 'deleted_by', 'call_id', 'ticket_status_id', 'created_by'
    ];

    protected $casts = [
        'knowledge_base' => 'boolean',
    ];

    protected $with = [
        'comments', 'status', 'calls', 'attachments', 'comment_attachments', 'brand', 'car_model', 'assigned_to', 'ask_for'
    ];

    protected $appends = ['subject_short'];

    public function getSubjectShortAttribute(): String
    {
        return Str::substr($this->attributes['subject'], 0, 25);
    }

    protected static function booted()
    {
        static::addGlobalScope(new RoleTicketFilterScope);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Department::class, 'department_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function deleted_by(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by', 'id');
    }

    public function attachments(): MorphToMany
    {
        return $this->morphToMany(\App\Models\Attachment::class, 'attachable');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(\App\Models\Comment::class, 'ticket_id', 'id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(\App\Models\TicketStatus::class, 'ticket_status_id', 'id');
    }

    public function calls(): HasMany
    {
        return $this->hasMany(\App\Models\Call::class, 'ticket_id', 'id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Brand::class, 'brand_id', 'id');
    }

    public function car_model(): BelongsTo
    {
        return $this->belongsTo(\App\Models\CarModel::class, 'car_model_id', 'id');
    }

    public function assigned_to(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to_id', 'id');
    }

    public function ask_for(): HasOne
    {
        return $this->hasOne(\App\Models\AskFors::class, 'id', 'ask_for_id');
    }

    public function comment_attachments(): HasManyThrough
    {
        return $this->hasManyThrough(Attachable::class, Comment::class, 'ticket_id', 'attachable_id', 'id', 'id')
            ->where('attachable_type', 'App\Models\Comment');
    }

    public function scopeAnswered($query)
    {
        return $query->where(function(Builder $q) {
                if(auth()->user()->roles[0]->id > 4) {
                    $q->whereAnswered(true);
                } else {
                    $q->whereAnswered(false);
                }
            });
    }

    public function scopeFilterTickets($query, String $type = 'tickets'): Builder
    {
        return $query->when($type === 'faqs', function (Builder $q, $faqs) {
            $q->where('knowledge_base', 1)
                ->when(request()->input('text'), function(Builder $q2, $text) {
                    $q2->where(function(Builder $q3) use ($text){
                        $q3->where('subject', 'LIKE', '%' . $text . '%')->orWhere('description', 'LIKE', '%' . $text . '%')
                            ->orWhere('other_brand_model', 'LIKE', '%' . $text . '%');
                    });
                })->when(request()->input('brand_id'), function(Builder $q, $brand_id) {
                    $q->whereHas('brand', function(Builder $q2) use ($brand_id){
                        $q2->where('id', $brand_id);
                    });
                })->when(request()->input('model_id'), function(Builder $q, $model_id) {
                    $q->whereHas('car_model', function(Builder $q2) use ($model_id){
                        $q2->where('id', $model_id);
                    });
                });
        })->when(request()->input('answered'), function (Builder $q, $answered) {
            if($answered == 1) {
                if(auth()->user()->roles[0]->id > 4) {
                    $q->whereAnswered(false);
                } else {
                    $q->whereAnswered(true);
                }
            } else {
                if(auth()->user()->roles[0]->id > 4) {
                    $q->whereAnswered(true);
                } else {
                    $q->whereAnswered(false);
                }
            }
        })->when(request()->input('ticket_id'), function (Builder $q, $id) {
            $q->where('custom_id', 'LIKE', '%' . $id . '%');
        })->when(request()->input('frame_id'), function (Builder $q, $frame_id) {
            $q->where('frame_id', 'LIKE', '%' . $frame_id . '%');
        })->when(request()->input('plate'), function (Builder $q, $plate) {
            $q->where('plate', 'LIKE', '%' . $plate . '%');
        })->when(request()->input('brand_id'), function (Builder $q, $brand) {
            $q->where('brand_id', $brand);
        })->when(request()->input('car_model_id'), function (Builder $q, $model) {
            $q->where('car_model_id', $model);
        })->when(request()->input('engine_type'), function (Builder $q, $engine_type) {
            $q->where('engine_type', 'LIKE', $engine_type . '%');
        })->when(request()->input('subject'), function (Builder $q, $subject) {
            $q->where('subject', 'LIKE', $subject . '%');
        })->when(request()->input('description'), function (Builder $q, $description) {
            $q->where('description', 'LIKE', $description . '%');
        })->when(request()->input('tests_done'), function (Builder $q, $tests_done) {
            $q->where('tests_done', 'LIKE', $tests_done . '%');
        })->when(request()->input('date_from'), function (Builder $q, $date_from) {
            $q->whereDate('tickets.updated_at', '>=', $date_from);
        })->when(request()->input('date_to'), function (Builder $q, $date_to) {
            $q->whereDate('tickets.updated_at', '<=', $date_to);
        })->when(request()->input('agent_id'), function (Builder $q, $agent_id) {
            $q->where('assigned_to_id', $agent_id);
        })->when(request()->input('knowledge_base'), function (Builder $q, $knowledge_base) {
            switch ($knowledge_base) {
                case '1':
                    $q->where('knowledge_base', 1);
                    break;

                default:
                    $q->where('knowledge_base', 0);
                    break;
            }
        })->when(request()->input('order_by'), function (Builder $q, $order_by) {
            $q->orderBy($order_by->field, $order_by->value);
        }, function ($q) {
            $q->orderBy('tickets.updated_at', 'DESC');
        })
            // SI ESTE TICKET ESTADO BUSCA POR EL ID DEL ESTADO
            ->when(request()->input('status'), function (Builder $q, $status_id) {

                if($status_id == 1) {
                    $q->where(function(Builder $q3) use ($status_id) {
                        if(auth()->user()->roles[0]->id > 4) { // Si es customer answered tiene que ser true porque HA SIDO contestado por algun admin
                            $q3->where('ticket_status_id', $status_id)->orWhere('answered', true);
                        } else {
                            $q3->where('ticket_status_id', $status_id)->orWhere('answered', false);
                        }
                    });
                } else {
                    $q->where('ticket_status_id', $status_id);
                }
        })
            // SI ESTE TICKET TIENE USUARIOS BUSCA POR EL NOMBRE
            ->when(request()->input('user_name'), function (Builder $q, $user_name) {
                $q->whereHas('user', function ($q2) use ($user_name) {
                    $q2->where('users.name', 'LIKE', '%' . $user_name . '%');
                });
            })
            // SI ESTE TICKET TIENE UN CLIENTE BUSCA POR EL ID
            ->when(request()->input('customer_custom_id'), function (Builder $q, $custom_id) {
                $q->whereHas('customer', function ($q2) use ($custom_id) {
                    $q2->where('customers.custom_id', 'LIKE', '%' . $custom_id . '%');
                });
            })
            // SI ESTE TICKET TIENE UN CLIENTE BUSCA POR EL ID
            ->when(request()->input('customer_name'), function (Builder $q, $customer_name) {
                $q->whereHas('customer', function ($q2) use ($customer_name) {
                    $q2->where('customers.comercial_name', 'LIKE', '%' . $customer_name . '%')
                        ->orWhere('customers.fiscal_name', 'LIKE', '%' . $customer_name . '%');
                });
            })
            // SI ESTE TICKET TIENE UN DEPARTAMENTO BUSCA POR EL ID
            ->when(request()->input('department_id'), function (Builder $q, $department_id) {
                $q->whereHas('department', function ($q2) use ($department_id) {
                    $q2->where('departments.id', $department_id);
                });
            })
            ->with(['user', 'customer', 'department', 'status'])
            // ->withCount(['comments', 'calls']);
            ->withCount(['comments']);
    }

    public static function getLastID()
    {
        $last_custom_id = Ticket::withoutGlobalScope(RoleTicketFilterScope::class)->withTrashed()->latest('id')->first()->custom_id;
        // SEPARA CUSTOM_ID POR GUION
        $array_custom_id = explode('-', $last_custom_id);
        // EXTRAE LOS ULTIMOS 4 CARACTERES DEL PRIMER ARRAY (DEPARTAMENTO AÑO)= AÑO
        $get_year_from_custom_id = substr($array_custom_id[0], -4);
        
        // SI EL AÑO ES EL MISMO EN EL QUE ESTAMOS SUMAMOS 1 AL CUSTOM_ID
        if($get_year_from_custom_id == now()->year) {
            return intval($array_custom_id[1]) + 1;
        }
        // SI EMPIEZA UN NUEVO AÑO RESETEAR CONTADOR A 1
        return 1;
    }
}
