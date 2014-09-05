<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tharutyunyan
 * Date: 4/9/14
 * Time: 6:43 PM
 * To change this template use File | Settings | File Templates.
 */


class LanguageBehavior extends CBehavior
{

    public function attach($owner)
    {
        $owner->attachEventHandler('onBeginRequest', array($this, 'handleLanguageBehavior'));
    }

    public function handleLanguageBehavior($event)
    {
        $app  = Yii::app();
        $user = $app->user;

        if (isset($_GET['_lang']))
        {
            $app->language = $_GET['_lang'];
            $user->setState('_lang', $_GET['_lang']);
            $cookie = new CHttpCookie('_lang', $_GET['_lang']);
            $cookie->expire = time() + (60 * 60 * 24 * 365); // (1 year)
            $app->request->cookies['_lang'] = $cookie;
            /*
            * другой код, например обновление кеша некоторых компонентов, которые изменяются при смене языка
            */
        }
        else if ($user->hasState('_lang'))
            $app->language = $user->getState('_lang');
        else if (isset($app->request->cookies['_lang']))
            $app->language = $app->request->cookies['_lang']->value;
    }

}