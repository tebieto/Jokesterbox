<?php
    $mc = new Memcached();
    $mc->addServer("127.0.0.1", 11211);

    $result = $mc->get("test_key");

    if($result) {
        echo $result;
    } else {
        echo "No data in cache. Please refresh page.";
        $mc->set("test_key", "test data pulled from cache!") or die ("Failed to save data in memcached server");
    }
    ?>
