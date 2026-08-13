<?php

/** @var yii\web\View $this */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = 'Profile';
$this->params['meta_description'] = 'User Profile';
$this->params['meta_keywords'] = 'profile, user';
$this->params['breadcrumbs'][] = $this->title;

$user = Yii::$app->user->identity;
$photo = $user->getPhoto();
$username = $user->username ?? 'Guest';
$initial = strtoupper((string) mb_substr($username, 0, 1));
$nickname = $user->getNickname();
?>

<div class="profile-index">
    <!-- Profile Header Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    <?php if (!empty($photo)): ?>
                        <img
                            src="<?= Html::encode($photo) ?>"
                            alt="<?= Html::encode($username) ?>"
                            class="rounded-circle"
                            style="width: 80px; height: 80px; object-fit: cover; border: 2px solid var(--dockify-primary-soft);"
                        >
                    <?php else: ?>
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px; background: var(--dockify-primary-soft); color: var(--dockify-primary); font-weight: 700; font-size: 2rem; border: 2px solid var(--dockify-primary-soft);"
                        >
                            <?= Html::encode($initial) ?>
                        </div>
                    <?php endif ?>
                </div>
                <div class="col">
                    <h2 class="mb-1"><?= Html::encode($nickname ?? $username) ?></h2>
                    <p class="text-muted mb-0">@<?= Html::encode($username) ?></p>
                    <p class="text-muted mb-0"><?= Html::encode($user->email) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Details -->
    <div class="row">
        <div class="col-lg-8">
            <!-- User Account Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="mb-0">Account Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Username:</strong></div>
                        <div class="col-sm-9"><?= Html::encode($user->username) ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Email:</strong></div>
                        <div class="col-sm-9"><?= Html::encode($user->email) ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Status:</strong></div>
                        <div class="col-sm-9">
                            <?php if ($user->status): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactive</span>
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Created:</strong></div>
                        <div class="col-sm-9"><?= Yii::$app->formatter->asDatetime($user->created_at) ?></div>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <?php $personalData = ArrayHelper::getValue($user->data, 'karyawan', []); ?>
            <?php if (!empty($personalData)): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="mb-0">Personal Information</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($personalData as $key => $value): ?>
                            <?php if (!empty($value) && !in_array($key, ['photo', 'karyawan_struktur_organisasi', 'jabatan_utama'])): ?>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong><?= Html::encode(implode(' ', array_map('ucfirst', preg_split('/[_-]/', $key)))) ?>
                                            :</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        <?php if (is_array($value)): ?>
                                            <ul class="list-unstyled mb-0">
                                                <?php foreach ($value as $subKey => $subValue): ?>
                                                    <li>
                                                        <?= Html::encode($subKey) ?>:
                                                        <?php if (is_array($subValue)): ?>
                                                            <code><?= Html::encode(json_encode($subValue)) ?></code>
                                                        <?php else: ?>
                                                            <?= Html::encode((string) $subValue) ?>
                                                        <?php endif ?>
                                                    </li>
                                                <?php endforeach ?>
                                            </ul>
                                        <?php else: ?>
                                            <?= Html::encode((string) $value) ?>
                                        <?php endif ?>
                                    </div>
                                </div>
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endif ?>

            <!-- Full Data Display -->
            <?php if (\app\enums\PermissionEnum::isSuperAdmin()): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="mb-0">Complete Data (SIHRD)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                <tr>
                                    <th>Key</th>
                                    <th>Value</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ((array) $user->data as $key => $value): ?>
                                    <?php if (is_array($value)): ?>
                                        <tr>
                                            <td><strong><?= Html::encode($key) ?></strong></td>
                                            <td>
                                                <details>
                                                    <summary class="cursor-pointer text-primary">View Details</summary>
                                                    <pre
                                                        class="mt-2 p-2 bg-light rounded"><?= Html::encode(json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
                                                </details>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td><?= Html::encode($key) ?></td>
                                            <td><?= Html::encode($value) ?></td>
                                        </tr>
                                    <?php endif ?>
                                <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="mb-0">Quick Info</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">User ID</small>
                        <strong><?= Html::encode($user->id) ?></strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Role</small>
                        <strong><?= Html::encode(implode(', ', array_keys(Yii::$app->authManager->getRolesByUser($user->id)))) ?: 'No Role' ?></strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Last Updated</small>
                        <strong><?= Yii::$app->formatter->asRelativeTime($user->updated_at) ?></strong>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .cursor-pointer {
        cursor: pointer;
    }

    details summary {
        list-style: none;
        cursor: pointer;
    }

    details summary::-webkit-details-marker {
        display: none;
    }
</style>
