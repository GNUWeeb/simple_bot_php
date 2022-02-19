<?php
// SPDX-License-Identifier: GPL-2.0-only

require __DIR__."/config.php";
const API_BASE_URL = "https://api.telegram.org/bot".TOKEN_BOT;

function curl(string $url, array $opt = []): ?string
{
	$optf = [
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HTTPHEADER => [
			"Content-Type: application/json"
		]
	];

	foreach ($opt as $k => $v)
		$optf[$k] = $v;

	$ch = curl_init($url);
	curl_setopt_array($ch, $optf);
	$out = curl_exec($ch);
	$err = curl_error($ch);
	$ern = curl_errno($ch);
	curl_close($ch);

	if ($err) {
		printf("Curl error: %d: %s\n", $ern, $err);
		return NULL;
	}

	return $out;
}

function sendMessage(string $text, int $chatId, array $extra = []): ?array
{
	$opt = [
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => json_encode(
			[
				"text" => $text,
				"chat_id" => $chatId
			] + $extra
		)
	];
	$out = curl(API_BASE_URL."/sendMessage", $opt);
	if (!$out)
		return NULL;

	return json_decode($out, true);
}

function send_covid19_data(int $chatId, string $country): ?array
{
	$raw  = file_get_contents(__DIR__."/worldometers_scraper/covid19.json");
	$json = json_decode($raw, true);

	$ref = &$json[$country];
	if (!isset($ref))
		return NULL;


	$text = "<b>Data COVID-19 for {$country}</b>\n".
		"<code>CMT:</code> {$ref["cmt"]}\n".
		"<code>FST:</code> {$ref["fst"]}\n".
		"<code>SDT:</code> {$ref["sdt"]}";

	return sendMessage($text, $chatId, ["parse_mode" => "HTML"]);
}

// function main(): int
// {
// 	$ret = 0;
// 	$out = send_covid19_data(-1001347566306, "USA");
// 	var_dump($out);

// 	return $ret;
// }

// exit(main());
