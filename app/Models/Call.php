<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class Call extends Model
{
    protected $fillable = [
        'id', 'customer_id', 'ticket_id', 'src', 'dst', 'dcontext', 'clid', 'channel', 'dstchannel', 'lastapp', 'lastdata', 'start', 
        'answer', 'end', 'duration', 'billsec', 'disposition', 'userfield', 'uniquefield', 'linkedid', 'is_incoming', 'is_outgoing',
        'is_to_billing', 'is_recorded', 'rating_status', 'src_extension', 'dst_extension', 'src_number', 'dst_number'
    ];
    
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Ticket::class, 'ticket_id', 'id');
    }
    
    /**
     * Get the customer that owns the Call
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public static function scopeFilterCalls($query) {
        return $query->when(request()->input('type'), function($q, $call_type) {
                switch ($call_type) {
                    case 'incoming':
                        $q->where('is_incoming', '=', 1)->where('is_outgoing', '=', 0)
                            ->when(request()->input('dst_number'), function($q, $dst_number) {
                                return $q->where('dst_number', $dst_number);
                            })->when(request()->input('src'), function($q, $src)  {
                                $src = str_replace(' ', '', $src);
                                return $q->where('src', 'LIKE', $src.'%');
                            })->when(request()->input('dst'), function($q, $dst)  {
                                $dst = str_replace(' ', '', $dst);
                                return $q->where('dst_extension', 'LIKE', $dst.'%');
                            });
                        break;
                    case 'outgoing':
                        $q->where('is_incoming', '=', 0)->where('is_outgoing', '=', 1)
                            ->when(request()->input('dst_number'), function($query, $dst_number) {
                                return $query->where('src_number', $dst_number);
                            })->when(request()->input('src'), function($query, $src)  {
                                $src = str_replace(' ', '', $src);
                                return $query->where('src', 'LIKE', $src.'%');
                            })->when(request()->input('dst'), function($query, $dst)  {
                                $dst = str_replace(' ', '', $dst);
                                return $query->where('dst', 'LIKE', $dst.'%');
                            });
                        break;
                    case 'finder':
                        $q->when(request()->input('phone_number'), function(Builder $q2, $phone_number){
                            $q2->where('src', 'LIKE', $phone_number . '%')->orWhere('dst', 'LIKE', $phone_number . '%');
                        });
                        break;
                    default:
                    break;
                }
            },
            // SI NO TIENE TIPO DE LLAMADA: entrante, saliente
            function($q2){
                $q2->when(request()->input('dst_number'), function($query, $dst_number) {
                    return $query->where(function($q2) use ($dst_number) {
                        $q2->where('src_number', $dst_number)->orWhere('dst_number', $dst_number);
                    });
                })->when(request()->input('src'), function($query, $src)  {
                    return $query->where(function($q3) use ($src) {
                        $q3->where('src', 'LIKE', $src.'%');
                    });
                })->when(request()->input('dst'), function($query, $dst)  {
                    $destino = str_replace(' ', '', $dst);
                    return $query->where(function($q3) use ($dst) {
                        $q3->where('dst_extension', 'LIKE', $dst.'%')->orWhere('dst', 'LIKE', $dst.'%');
                    });
                });
            })->when(request()->input('extension_id'), function(Builder $q, $extension_id) {
                return $q->where(function($q2) {
                    $q2->where('src', 'LIKE', auth()->user()->extension->extension_number)
                        ->orWhere('dst', 'LIKE', auth()->user()->extension->extension_number);
                });
            })->when(request()->input('dateFrom'), function($query, $dateFrom) {
                return $query->whereDate('start','>=', $dateFrom);
            }, function($query){
                return $query->whereDate('start', '>=', Carbon::parse('first day of last month'));
            })->when(request()->input('dateTo'), function($query, $dateTo) {
                return $query->whereDate('start', '<=', $dateTo);
            }, function($q) {
                $q->whereDate('start', '<=', Carbon::parse('last day of this month'));
            })->when(request()->input('disposition'), function($query, $disposition)  {
                return $query->where('disposition', $disposition);
            })->when(request()->input('customer_id'), function(Builder $q, $customer_id) {
                $q->where('customer_id', $customer_id);
            });
    }
}
