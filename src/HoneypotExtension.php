<?php
declare(strict_types = 1);

namespace wodCZ\NetteHoneypot;

use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;

class HoneypotExtension extends CompilerExtension
{
    /** @var array */
    protected $defaultConfig = [
        'inline' => true
    ];

    public function afterCompile(ClassType $class) : void
    {
        $config = $this->getConfig($this->defaultConfig);

        $initialize = $class->methods['initialize'];
        $initialize->addBody('wodCZ\NetteHoneypot\Honeypot::register(?);', [$config['inline']]);
    }
}
