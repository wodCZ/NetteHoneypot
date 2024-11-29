<?php
declare(strict_types = 1);

namespace wodCZ\NetteHoneypot;

use Nette\Forms\Container;
use Nette\Forms\Controls\BaseControl;
use Nette\Utils\Html;

class Honeypot extends BaseControl
{

    public const MODE_CSS = "css";
    public const MODE_JS = "js";

    /**
     * @var bool
     */
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
    /**
     * @var array
     */
    public $onError = [];

    public function __construct(
        $caption = null,
        ?string $message = null,
        string $mode = self::MODE_JS,
        bool $inline = true
    ) {
        parent::__construct($caption);

        if (is_null($message)) {
            $message = "Please, don't fill this field";
        }

        $this->inline = $inline;

        $this->control->type = 'text';

        $this->cssFile = __DIR__ . '/assets/style.css';
        $this->jsFile = __DIR__ . '/assets/script.js';
        $this->mode = $mode;
        $this->message = $message;

        $this->onError[] = function ($control) {
            $control->addError($this->message);
        };
    }

    /**
     * Generates control's HTML element.
     * @return Html|string
     */
    public function getControl() : Html
    {
        $control = parent::getControl();
        $label = parent::getLabel();

        $control->addAttributes(["autocomplete"=>"new-password"]);

        $container = Html::el('div');
        $container->id = $control->id . '-container';
        $container->class = 'wodcz-nette-forms-hp';
        $container->addHtml($label);
        $container->addHtml($control);

        if ($this->inline) {
            if ($this->mode == self::MODE_JS) {
                $script = Html::el('script')->setType('text/javascript')->setHtml(file_get_contents($this->jsFile));
                $container->addHtml($script);
            } elseif ($this->mode == self::MODE_CSS) {
                $style = Html::el('style')->setText(file_get_contents($this->cssFile));
                $container->addHtml($style);
            }
        }


        return $container;
    }

    /**
     * Generates label's HTML element - here we dont need label
     * @param  null|string|object  $caption
     * @return Html|string|null
     */
    public function getLabel($caption = null) : ?string
    {
        return null;
    }

    public function validate() : void
    {
        parent::validate();

        $value = $this->getValue();
        if (!empty($value)) {
            $this->onError($this);
        }
    }

    public static function register(bool $inline = true) : void
    {
        Container::extensionMethod(
            'addHoneypot',
            static function ($container, $name, $caption = null, $message = null, $mode = self::MODE_JS) use ($inline) {
                return $container[$name] = new self($caption, $message, $mode, $inline);
            }
        );
    }
}
