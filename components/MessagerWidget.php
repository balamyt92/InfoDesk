<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class MessagerWidget
 * @package app\components
 */

class MessagerWidget extends Widget
{
    public $message;

    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = 'Hello World';
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        return Html::encode($this->message);
    }
}