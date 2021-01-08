<?php
declare(strict_types = 1);

namespace wodCZ\NetteHoneypot;

use Nette;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;

class HoneypotExtension extends CompilerExtension
{

    public function getConfigSchema(): Nette\Schema\Schema
    {
        return Nette\Schema\Expect::structure(["inline" => Nette\Schema\Expect::bool(true)]);
    }



    public function afterCompile(ClassType $class): void
    {
        $config = $this->getConfig();

        $initialize = $class->methods['initialize'];
        $initialize->addBody('wodCZ\NetteHoneypot\Honeypot::register(?);', [$config->inline]);
    }
}
