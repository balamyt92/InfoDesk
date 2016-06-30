<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('Вижу Hello на главной');
$I->amOnPage('/');
$I->see('Hello');
