<?php

namespace app\enums;

enum PermissionEnum: string {
    case SUPER_ADMIN = 'super-admin';
    case ADMIN = 'admin';

    public function label(): string {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN => 'Admin',
        };
    }


    public static function isSuperAdmin(): bool {
        return \Yii::$app->user->can(self::SUPER_ADMIN->value);
    }

}
