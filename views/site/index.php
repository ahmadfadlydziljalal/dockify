<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Dashboard';
$this->params['meta_description'] = 'A high-performance PHP framework best for developing web applications. Fast, secure, and professional.';
$this->params['meta_keywords'] = 'yii, yii2, php, framework, web application, high-performance';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <div class="row g-3">
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-3 extension-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="fs-4"> <i class="bi bi-bug"></i></span>
                        <h3 class="h6 fw-bold mb-0 ms-2">Testing (TDD & BDD)</h3>
                    </div>
                    <div class="text-body-secondary small mb-0">
                        <?= Html::ol([
                            '✅ Unit Test',
                            '✅ Functional Test',
                            '✅ Acceptance Test',
                        ]) ?>

                        <hr>

                        <p class="text-muted">Langkah - langkahnya:</p>
                        <?= Html::ol([
                            'Lengkapi `env` file dengan data-data yang diperlukan. Cari dengan prefix `TESTING_`',
                            'Buat dummy data untuk user: ' . Html::tag('code', 'docker compose exec php yii fixture/generate user'),
                            'Lakukan migrate untuk database testing: ' . Html::tag('code', 'docker compose exec php php tests/Support/bin/yii migrate/up'),
                            'Buka di browser untuk melihat Live Acceptance Testing: di '. Html::tag('code', 'http://localhost:7900/') . ' dengan password: <strong>secret</strong>',
                            'Unit Testing: ' .Html::tag('code', 'docker compose exec -T php codecept run Unit'),
                            'Functional Testing: ' . Html::tag('code', 'docker compose exec -T php codecept run Functional'),
                            'Acceptance Testing: ' . Html::tag('code', 'docker compose exec -T php codecept run Acceptance'),
                            'All Testing: ' . Html::tag('code', 'docker compose exec -T php codecept run'),
                        ],[
                            'encode' => false,
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-3 extension-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="fs-4"> <i class="bi bi-hexagon"></i></span>
                        <h3 class="h6 fw-bold mb-0 ms-2">Extension</h3>
                    </div>
                    <div class="text-body-secondary small mb-0">
                        <?= Html::ol([
                            '✅ yii2-auth-client',
                            '✅ yii2-dotenv',
                            '✅ yii2-admin',
                            '✅ yii2-autonumber',
                            '✅ yii2-settings',
                            '✅ yii2-queue',
                            '✅ yii2-bootstrap5-dropdown',
                            '✅ yii2-widget-activeform',
                            '✅ yii2-widget-datepicker',
                            '✅ yii2-widget-datetimepicker',
                            '✅ yii2-datecontrol',
                            '✅ yii2-widget-select2',
                            '✅ yii2-widget-depdrop',
                            '✅ yii2-widget-fileinput',
                            '✅ yii2-mpdf',
                            '✅ yii2-export',
                            '✅ ahmadfadlydziljalal/yii2-crud',
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
