<?php 

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class RoleTicketFilterScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \App\Models\User  $user
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $user = auth()->user();
        if(auth()->check()) {
            if(!$user->hasAnyRole([1, 2, 3])) {
            // if($user->hasRole([1, 2, 3])) {
                $builder->where('customer_id', '=', $user->customer_id);
            }
        } else {
            return redirect('login');
        }
    }
}
