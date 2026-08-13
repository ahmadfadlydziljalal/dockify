<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

?>

<!-- Extensions grid -->
<div class="row g-3">

    <!-- Debug -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm rounded-3 extension-card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="extension-icon" aria-hidden="true">&#128270;</span>
                    <h3 class="h6 fw-bold mb-0 ms-2">yii2-debug</h3>
                </div>
                <p class="text-body-secondary small mb-0">
                    Debug toolbar and debugger for Yii2. Inspect logs, database queries,
                    request data, and application performance in real time.
                </p>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <?= Html::a(
                    'Learn more &raquo;',
                    'https://www.yiiframework.com/extension/yiisoft/yii2-debug',
                    [
                        'class' => 'btn btn-sm btn-outline-secondary',
                        'rel' => 'noopener',
                        'target' => '_blank',
                    ],
                ) ?>
            </div>
        </div>
    </div>

    <!-- Queue -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm rounded-3 extension-card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="extension-icon" aria-hidden="true">&#128203;</span>
                    <h3 class="h6 fw-bold mb-0 ms-2">yii2-queue</h3>
                </div>
                <p class="text-body-secondary small mb-0">
                    Asynchronous job queue with support for DB, Redis, AMQP, Beanstalk,
                    and SQS drivers. Run background tasks with ease.
                </p>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <?= Html::a(
                    'Learn more &raquo;',
                    'https://www.yiiframework.com/extension/yiisoft/yii2-queue',
                    [
                        'class' => 'btn btn-sm btn-outline-secondary',
                        'rel' => 'noopener',
                        'target' => '_blank',
                    ],
                ) ?>
            </div>
        </div>
    </div>

    <!-- Custom Crud -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm rounded-3 extension-card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="extension-icon" aria-hidden="true">&#9881;</span>
                    <h3 class="h6 fw-bold mb-0 ms-2">yii2-crud</h3>
                </div>
                <p class="text-body-secondary small mb-0">
                    <strong>Custom Gii</strong> with CRUD generator by <?= Html::a('ahmadfadlydziljalal', 'https://github.com/ahmadfadlydziljalal', ['target' => '_blank']) ?> for Yii2. Generate models, controllers, and views with advanced features and customization options.
                </p>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <?= Html::a(
                    'Learn more &raquo;',
                    'https://github.com/ahmadfadlydziljalal/yii2-crud',
                    [
                        'class' => 'btn btn-sm btn-outline-secondary',
                        'rel' => 'noopener',
                        'target' => '_blank',
                    ],
                ) ?>
            </div>
        </div>
    </div>

    <!-- Auth Client -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm rounded-3 extension-card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="fs-4"> <i class="bi bi-house"></i></span>
                    <h3 class="h6 fw-bold mb-0 ms-2">yii2-auth-client</h3>
                </div>
                <p class="text-body-secondary small mb-0">
                    Auth Client integration for Yii2. Authenticate users via OAuth, OpenID, and other protocols with popular providers like Google, Facebook, GitHub, and SIHRD.
                </p>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <?= Html::a(
                    'Learn more &raquo;',
                    'https://www.yiiframework.com/extension/yiisoft/yii2-authclient/doc/guide/2.2/en',
                    [
                        'class' => 'btn btn-sm btn-outline-secondary',
                        'rel' => 'noopener',
                        'target' => '_blank',
                    ],
                ) ?>
            </div>
        </div>
    </div>

    <!-- Dotenv -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm rounded-3 extension-card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="fs-4"> <i class="bi bi-hexagon"></i></span>
                    <h3 class="h6 fw-bold mb-0 ms-2">yii2-dotenv</h3>
                </div>
                <p class="text-body-secondary small mb-0">
                   Dotenv integration for Yii2. Load environment variables from a .env file into your application configuration.
                </p>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <?= Html::a(
                    'Learn more &raquo;',
                    'https://github.com/panlatent/yii2-dotenv',
                    [
                        'class' => 'btn btn-sm btn-outline-secondary',
                        'rel' => 'noopener',
                        'target' => '_blank',
                    ],
                ) ?>
            </div>
        </div>
    </div>

    <!-- Admin -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm rounded-3 extension-card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="fs-4"> <i class="bi bi-send-check"></i></span>
                    <h3 class="h6 fw-bold mb-0 ms-2">yii2-admin</h3>
                </div>
                <p class="text-body-secondary small mb-0">
                    Admin integration for Yii2. Manage user roles, permissions, and access control within your application.
                </p>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <?= Html::a(
                    'Learn more &raquo;',
                    'https://github.com/mdmsoft/yii2-admin',
                    [
                        'class' => 'btn btn-sm btn-outline-secondary',
                        'rel' => 'noopener',
                        'target' => '_blank',
                    ],
                ) ?>
            </div>
        </div>
    </div>

    <!-- Auto Number -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm rounded-3 extension-card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="fs-4"> <i class="bi bi-sort-numeric-down"></i></span>
                    <h3 class="h6 fw-bold mb-0 ms-2">yii2-auto-number</h3>
                </div>
                <p class="text-body-secondary small mb-0">
                    Auto Number integration for Yii2. Automatically generate sequential numbers for your models.
                </p>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <?= Html::a(
                    'Learn more &raquo;',
                    'https://github.com/mdmsoft/yii2-autonumber',
                    [
                        'class' => 'btn btn-sm btn-outline-secondary',
                        'rel' => 'noopener',
                        'target' => '_blank',
                    ],
                ) ?>
            </div>
        </div>
    </div>

    <!-- Yii2 Settings -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm rounded-3 extension-card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="fs-4"> <i class="bi bi-gear"></i></span>
                    <h3 class="h6 fw-bold mb-0 ms-2">yii2-settings</h3>
                </div>
                <p class="text-body-secondary small mb-0">
                    Settings integration for Yii2. Manage application settings and configurations easily.
                </p>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <?= Html::a(
                    'Learn more &raquo;',
                    'https://github.com/phemellc/yii2-settings',
                    [
                        'class' => 'btn btn-sm btn-outline-secondary',
                        'rel' => 'noopener',
                        'target' => '_blank',
                    ],
                ) ?>
            </div>
        </div>
    </div>

    <!-- Symfony Mailer -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm rounded-3 extension-card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="extension-icon" aria-hidden="true">&#9993;</span>
                    <h3 class="h6 fw-bold mb-0 ms-2">yii2-symfonymailer</h3>
                </div>
                <p class="text-body-secondary small mb-0">
                    Email sending integration powered by Symfony Mailer.
                    Compose and deliver rich HTML emails with attachments and templates.
                </p>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <?= Html::a(
                    'Learn more &raquo;',
                    'https://github.com/yiisoft/yii2-symfonymailer',
                    [
                        'class' => 'btn btn-sm btn-outline-secondary',
                        'rel' => 'noopener',
                        'target' => '_blank',
                    ],
                ) ?>
            </div>
        </div>
    </div>
</div>
