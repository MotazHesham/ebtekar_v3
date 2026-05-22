<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;

class ShippingRoleAssigner
{
    /** @var array<string, string> user_type => role title */
    public const USER_TYPE_TO_ROLE = [
        'shipping_partner'  => 'Shipping Partner',
        'courier'           => 'Courier',
        'delivery_man'      => 'Courier',
        'dispatcher'        => 'Dispatcher',
        'receiving_clerk'   => 'Receiving Clerk',
        'delivery_cs'       => 'Delivery Customer Service',
    ];

    public static function roleTitleFor(?string $userType): ?string
    {
        if (! $userType) {
            return null;
        }

        return self::USER_TYPE_TO_ROLE[$userType] ?? null;
    }

    public static function shippingRoleTitles(): array
    {
        return array_values(array_unique(self::USER_TYPE_TO_ROLE));
    }

    public static function assign(User $user): void
    {
        $title = self::roleTitleFor($user->user_type);

        if ($title) {
            $role = Role::where('title', $title)->first();
            if ($role) {
                $user->roles()->sync([$role->id]);
            }

            return;
        }

        $roleIds = Role::whereIn('title', self::shippingRoleTitles())->pluck('id');
        if ($roleIds->isNotEmpty()) {
            $user->roles()->detach($roleIds);
        }
    }

    public static function assignAllUsers(): int
    {
        $count = 0;

        User::query()
            ->whereIn('user_type', array_keys(self::USER_TYPE_TO_ROLE))
            ->each(function (User $user) use (&$count) {
                self::assign($user);
                $count++;
            });

        return $count;
    }
}
