<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use FormBuilder\FormBuilder as Form;

$array = ['products' => ['desk' => ['price' => 100]]];


$form = (new Form('cccc', 'POST'))
    ->inputText('pan', $card['number'])
    ->inputText('cv2', $card['cv2'])
    ->inputText('Ecom_Payment_Card_ExpDate_Year', $card['exp_year'])
    ->inputText('Ecom_Payment_Card_ExpDate_Month', $card['exp_month']);


print_r($form);