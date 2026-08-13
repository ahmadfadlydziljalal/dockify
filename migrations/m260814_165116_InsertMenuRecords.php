<?php

use yii\db\Migration;

class m260814_165116_InsertMenuRecords extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void {
        $this->truncateTable('menu');
        $this->execute(<<<SQL
            INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES (1, 'Top Menu', NULL, NULL, NULL, NULL);
            INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES (2, 'Left Menu', NULL, NULL, NULL, NULL);
            INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES (3, 'Right Menu', NULL, NULL, NULL, NULL);
            INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES (4, 'Dashboard', 2, '/site/index', NULL, 'return[\'icon\' => \'speedometer\'];');
            INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES (5, 'Trustee', 2, NULL, NULL, 'return[\'icon\' => \'cone\'];');
            INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES (6, 'Menu', 5, '/admin/menu/index', NULL, 'return[\'icon\' => \'play\'];');
            INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES (7, 'Assignments', 5, '/admin/assignment/index', NULL, 'return[\'icon\' => \'play\'];');
            INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES (8, 'Permissions', 5, '/admin/permission/index', NULL, 'return[\'icon\' => \'play\'];');
            INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES (9, 'Roles', 5, '/admin/role/index', NULL, 'return[\'icon\' => \'play\'];');
            INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES (10, 'Rules', 5, '/admin/rule/index', NULL, 'return[\'icon\' => \'play\'];');
            INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES (11, 'Routes', 5, '/admin/route/index', NULL, 'return[\'icon\' => \'play\'];');
            INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES (12, 'Settings', 5, '/settings/default/index', NULL, 'return[\'icon\' => \'play\'];');
            INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES (13, 'Users', 5, '/admin/user/index', NULL, 'return[\'icon\' => \'play\'];');
            INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES (14, 'Development', 2, NULL, NULL, 'return[\'icon\' => \'code-slash\'];');
            INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES (15, 'Debug', 14, '/debug/default/index', NULL, 'return[\'icon\' => \'play\'];');
            INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES (16, 'Gii', 14, '/gii/default/index', NULL, 'return[\'icon\' => \'play\'];');
            INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES (17, 'Profile', 3, '/profile/index', NULL, 'return[\'icon\' => \'person\'];');
            INSERT INTO `menu` (`id`, `name`, `parent`, `route`, `order`, `data`) VALUES (18, 'Session', 5, '/session/index', NULL, 'return[\'controller\' => \'session\', \'icon\' => \'play\'];');


        SQL
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void {
        $this->truncateTable('menu');
    }

}
