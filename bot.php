<?php
// SPDX-License-Identifier: GPL-2.0-only

require_once __DIR__."/config.php";
require_once __DIR__."/lib.php";

$j = json_decode(file_get_contents("php://input"), true);

// // Debug only.
// $j = json_decode(file_get_contents(__DIR__."/test.json"), true);

$ret = 0;

if (isset($j["message"]["text"]))
	$ret = handle_text_response($j);

return [
	"code" => 200,
	"msg" => "handled!"
];

function handle_text_response(array $j): int
{
	$text = $j["message"]["text"];

	if (!preg_match("/\/cvd\s+(.+)$/", $text, $m))
		return 1;

	send_covid19_data($j["message"]["chat"]["id"], trim($m[1]), $j["message"]["message_id"]);
	return 0;
}
