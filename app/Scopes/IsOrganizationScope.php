<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class IsOrganizationScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $id = App::get('request')->input('client_id');
        if(!empty(Request::input('oauth_client_id'))){
            $id = Request::input('oauth_client_id');
        }
        if(!empty(Auth::user()->organization_id)){
            $id = Auth::user()->organization_id;
        }

        $builder->where('organization_id', $id);
    }
}