<?php

session_start();

$BASE_URL = "http://" . $_SERVER['SERVER_NAME']. ':9090' . dirname($_SERVER['REQUEST_URI'] . '?') . '/';