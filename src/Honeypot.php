<?php

namespace wodCZ\NetteHoneypot;


use Nette\Forms\Container;
use Nette\Forms\Controls\BaseControl;
use Nette\Utils\Html;

class Honeypot extends BaseControl
{

    const MODE_CSS = "css";
    const MODE_JS = "js";

    private $inline;

    /**
     * @var string
     */
    private $jsFile;
    /**
     * @var string
     */
    private $cssFile;
    /**
     * @var string
     */
    private $mode;
    /**
     * @var null|string
     */
    private $message;

    public $onError = [];

    public function __construct($caption = NULL, $message = NULL, $mode = self::MODE_JS, $inline = TRUE)
    {
        parent::__construct($caption);

        if(is_null($message)){
            $message = "Please, don't fill this field";
        }

        $this->inline = $inline;

        $this->control->type = "text";

        $this->cssFile = __DIR__ . '/assets/style.css';
        $this->jsFile = __DIR__ . '/assets/script.js';
        $this->mode = $mode;
        $this->message = $message;

        $this->onError[] = function($control){
            $control->addError($this->message);
        };
    }

    public function getControl()
    {
        $control = parent::getControl();
        $label = parent::getLabel();

        $container = Html::el('div');
        $container->id = $control->id . "-container";
        $container->class = 'wodcz-nette-forms-hp';
        $container->add($label);
        $container->add($control);

        if ($this->inline) {
            if ($this->mode == self::MODE_JS) {
                $script = Html::el('script')->setType('text/javascript')->setHtml(file_get_contents($this->jsFile));
                $container->add($script);
            } elseif ($this->mode == self::MODE_CSS) {
                $style = Html::el('style')->setText(file_get_contents($this->cssFile));
                $container->add($style);
            }
        }


        return $container;
    }

    public function getLabel($caption = NULL)
    {
        return null;
    }

    public function validate()
    {
        parent::validate();

        $value = $this->getValue();
        if(!empty($value)){
            $this->onError($this);
        }

    }


    public static function register($inline = TRUE)
    {
        Container::extensionMethod('addHoneypot', function ($container, $name, $caption = NULL, $message = NULL, $mode = self::MODE_JS) use ($inline) {
            return $container[$name] = new self($caption, $message, $mode, $inline);
        });
    }
}