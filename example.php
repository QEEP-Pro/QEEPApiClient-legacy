<?php
    require_once "bootstrap.php";

    const TIME_STAMP = 1438650151;
    $createdTime = 1597440633;
    $orderId = $createdTime - TIME_STAMP;
    $api = new APIController();

    $response = $api->createOrder(
        strval($orderId),
        '89924455041',
        array(
            0 => array(
                'code' => 15,
                'amount' => 99,
                'price' => 75000,
            ),
        ),
        "50000",
        'new.domain',
        "Никита",
        strval($createdTime)
    );
    
    echo "Status:\n";
    if ($response) {
        echo "Success!";
    } else {
        echo "Error!";
    }
    $response_cancel = $api->cancelOrder($orderId);
    
    $response_catalog = $api->getCatalog();
    print_r($response_catalog);
