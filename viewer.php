<?php
// TODO: upload
$filename = 'ON_SCHET___20201029_0d1c7e82-857c-4df0-b7fd-5b13e73f354f.xml'; 

$xml = simplexml_load_file(__DIR__ . '/' . $filename);

processEDOSch($xml);

/**
 * Парсит и выводит в нормально виде ЭДОСч (счет)
 * https://sbis.ru/formats/docFormatCardEdo/1222/format/
 */
function processEDOSch(SimpleXMLElement $xml) {
	$name = $xml->getName();
	if ($name != 'Файл' ||  $xml['Формат'] != 'ЭДОСч') {
		die('Файл не соответсвует формату ЭДОСч');
	}

	$doc = $xml->Документ;
	$provider = $doc->Поставщик;
	$customer = $doc->Покупатель;
	$invoce = $doc->ТаблДок;
?>
	<article>
		TODO: таблица реквизитов
		<h1><?=$doc['Название'];?> №<?=$doc['Номер'];?> от <?=$doc['Дата'];?></h1>
		TODO: поставщик
		TODO: покупатель
		TODO: Основание

	</article>
<?php
}