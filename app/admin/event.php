<?php
// 事件定义文件
return [
    'bind'      => [
        'AdminLoginAfter' => 'app\admin\listener\AdminLoginAfter',
        'AdminLoginErrorAfter' => 'app\admin\listener\AdminLoginErrorAfter',
        'AdminLogoutAfter' => 'app\admin\listener\AdminLogoutAfter',
    ],

    'listen'    => [
        'AdminLoginAfter' => ['app\admin\listener\AdminLoginAfter'],
        'AdminLoginErrorAfter' => ['app\admin\listener\AdminLoginErrorAfter'],
        'AdminLogoutAfter' => ['app\admin\listener\AdminLogoutAfter'],
    ],

    'subscribe' => [
    ],
];
