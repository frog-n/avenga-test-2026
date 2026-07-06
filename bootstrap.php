<?php

$app = new \App\Application(
    realpath(__DIR__) ?: ''
);

$app->boot();