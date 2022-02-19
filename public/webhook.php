<?php

require __DIR__."/../config.php";

if (!isset($_GET["key"])) {
	$code = 400;
	$msg  = "Missing key!";
	goto out;
}

if ($_GET["key"] !== WEBHOOK_KEY) {
	$code = 401;
	$msg = "Unauthorized!";
	goto out;
}

require __DIR__."/../bot.php";

out:
http_response_code($code);
header("Content-Type: application/json");
echo json_encode(
	[
		"code" => $code,
		"msg" => $msg
	],
	JSON_PRETTY_PRINT
);
