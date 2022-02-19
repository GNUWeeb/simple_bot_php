<?php
// SPDX-License-Identifier: GPL-2.0-only

require __DIR__."/lib.php";

$json = json_decode(file_get_contents("php://input"), true);

file_put_contents(__DIR__."/public/json.json", json_encode($json, JSON_PRETTY_PRINT));
