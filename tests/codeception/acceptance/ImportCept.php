<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Проверям страницу исморта');
$I->amOnPage('/import');
$I->see('Импорт');