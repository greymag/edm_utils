<?php
// TODO: upload
$filename = 'ON_SCHET___20201029_0d1c7e82-857c-4df0-b7fd-5b13e73f354f.xml'; 

$xml = simplexml_load_file(__DIR__ . '/' . $filename);

processEDOSch301($xml);

/**
 * Парсит и выводит в нормально виде ЭДОСч (счет)
 * https://sbis.ru/formats/docFormatCardEdo/47273/format/
 */
function processEDOSch301(SimpleXMLElement $xml) {
	$name = $xml->getName();
	if ($name != 'Файл' ||  $xml['Формат'] != 'ЭДОСч' || $xml['ВерсияФормата'] != '3.01') {
		die('Файл не соответсвует формату ЭДОСч версии 3.01');
	}

	$doc = $xml->Документ;
	$provider = $doc->Поставщик;
	$customer = $doc->Покупатель;
	$invoce = $doc->ТаблДок;
	$total = $invoce->ИтогТабл;
	$totalVat = $total->НДС;
	$hasVat = !empty($totalVat) && !empty((float)$totalVat['СУММА']);
?>
	<article>
		TODO: таблица реквизитов
		<h1><?=$doc['Название'];?> №<?=$doc['Номер'];?> от <?=$doc['Дата'];?></h1>
		TODO: поставщик
		TODO: покупатель
		TODO: Основание
		<table>
			<tr>
				<th>№</th>
				<th>Код</th>
				<th>Товары</th>
				<th>Кол-во</th>
				<th>Цена</th>
				<th>Сумма</th>
			</tr>
			<?php foreach ($invoce->СтрТабл as $entry): ?>
			<tr>
				<td><?=$entry['ПорНомер'];?></td>
				<td><?=$entry['Код'];?></td>
				<td><?=$entry['Название'];?></td>
				<td><?=$entry['Кол_во'];?></td>
				<td><?=$entry['Цена'];?></td>
				<td><?=$entry['Сумма'];?></td>
			</tr>
			<?php endforeach; ?>
		</table>

		<table>
			<tr>
				<td>Итого:</td>
				<td><?=$total['Сумма'];?></td>
			</tr>
			<tr>
				<td>В том числе НДС:</td>
				<td><?=$hasVat ? $totalVat['СУММА'] : '-';?></td>
			</tr>
			<tr>
				<td>Всего к оплате:</td>
				<td><?=$total['Сумма'];?></td>
			</tr>
		</table>
	</article>
<?php
}