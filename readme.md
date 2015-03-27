# Nette Honeypot Extension

Adds support for honeypot input in Nette Forms.

## What it does

[Article about honeypot input](http://haacked.com/archive/2007/09/11/honeypot-captcha.aspx/)

TLDR; It creates form input, then hides it using CSS or JS. Spam bots usually fill all fields 
(especially those with yummy names, like 'email' or 'web'). 
Human will not fill hidden field, so this is a way to detect bot, instead of forcing user to solve captcha.

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
