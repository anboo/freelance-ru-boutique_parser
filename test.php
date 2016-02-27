<?php
/**
 * Created by PhpStorm.
 * User: anboo
 * Date: 27.02.16
 * Time: 7:05
 */

require_once('FreelanceRequest.php');

$request = new FreelanceRequest();
$request->setCookie('cookie_test.txt');
$request->setLogin('rusdteam');
$request->setPassword('******');
//$request->setProxy('');

$htmlFromLoginFreelanceRu = $request->loginFreelanceRu();
$htmlFromLoginFreelanceBoutique = $request->loginFreelanceBoutique();

$htmlViewPage = $request->showPage('https://freelance.boutique/user/profile/view');
echo $htmlViewPage;

/**
Create project
*/

$formData = [
    'name'      => 'Правки по сайту на Ruby On Rails',
    'cost'      => '7500',
    'payment[]' => 'bank',
    'interval'  => '2',
    'descr'     => 'Нужно исправить пару багов на сайте. Подробное ТЗ после принятия проекта.',
    'must'      => 'Обязательных требований нет'
];

$request->createProjectFor('danieldebil', $formData);

