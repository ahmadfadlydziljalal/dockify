<?php

namespace app\controllers;

class ProfileController extends \yii\web\Controller
{
    public function actionIndex(): string {
        return $this->render('index');
    }

}
