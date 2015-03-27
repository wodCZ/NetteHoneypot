# Nette Honeypot Extension

Adds support for honeypot input in Nette Forms.

## Installation

Best way to install this is using composer:

	composer require wodcz/nette-honeypot
	
Then register extension:
	
	extensions:
        honeypot: wodCZ\NetteHoneypot\HoneypotExtension
        
## Usage

	$form->addHoneypot($name, $caption, $errorMessage, $mode);
	
`$name` should be something yummy for robot, like email.

In `$caption` you should write something for user, which for some reason has not this field hidden.

In `$message` you may change default error message.

And `$mode` should be one of wodCZ\NetteHoneypot\Honeypot::`MODE_CSS` or `MODE_JS`.
	 
You can also specify your own error callback:

	$honeypot = $form->addHoneypot('email');
	$honeypot->onError[] = function($control){ .... };

## Configuring

	honeypot:
		inline: true/false # if true, extension will append css/js (according to mode) right after input. Otherwise you have to link css/js on your own.
