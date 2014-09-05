<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tharutyunyan
 * Date: 4/9/14
 * Time: 6:37 PM
 * To change this template use File | Settings | File Templates.
 */

class LanguageSelector extends CWidget {
    public function run() {
        $currentLang = Yii::app()->language;
        $languages = Yii::app()->params->languages;
        $this->render('languageSelector', array('currentLang' => $currentLang, 'languages'=>$languages));
    }
}