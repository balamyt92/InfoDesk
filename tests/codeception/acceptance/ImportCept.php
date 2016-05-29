<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Проверям страницу импорта');
$I->amOnPage('/import');
$I->see('Импорт');