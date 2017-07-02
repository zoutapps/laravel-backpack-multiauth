<?php
/**
 * Created by PhpStorm.
 * User: Oli
 * Date: 02.07.17
 * Time: 02:13
 */

namespace ZoutApps\LaravelBackpackMultiAuth\Traits;

/**
 * Trait OverridesRolesAndPermissionsRelations
 * @package ZoutApps\LaravelBackpackMultiAuth\Traits
 *
 * Overrides relations to prevent eloquent automatically assuming the foreign key name
 */
trait OverridesRolesAndPermissionsRelations
{

    /**
     * A user may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(
            config('laravel-permission.models.role'),
            config('laravel-permission.table_names.user_has_roles'),
            'user_id',
            'role_id',
            'user'
        );
    }
    /**
     * A user may have multiple direct permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(
            config('laravel-permission.models.permission'),
            config('laravel-permission.table_names.user_has_permissions'),
            'user_id',
            'permission_id',
            'user'
        );
    }
}