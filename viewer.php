<?php

$error = null;
if (isset($_FILES['xml'])) {
    $xml = uploadXml($_FILES['xml']);
    
    if (!empty($xml)) {
    } else {
        $error = 'Не удалось загрузить XML.';
    }
}


if (empty($xml)) {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'form.html';
} else {
    processEDOSch301($xml);
}


function uploadXml($data)
{
    $path = $data['tmp_name'];
    return simplexml_load_file($path);
}

/**
 * Парсит и выводит в нормально виде ЭДОСч (счет)
 * https://sbis.ru/formats/docFormatCardEdo/47273/format/
 */
function processEDOSch301(SimpleXMLElement $xml)
{
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
    
    $title = $doc['Название'] . ' №' . $doc['Номер'] . ' от ' . $doc['Дата']; ?>
<head>
	<title><?=$title; ?></title>
</head>
<body>
	<article>
		TODO: таблица реквизитов
		<h1><?=$title; ?></h1>
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
				<td><?=$entry['ПорНомер']; ?></td>
				<td><?=$entry['Код']; ?></td>
				<td><?=$entry['Название']; ?></td>
				<td><?=$entry['Кол_во']; ?></td>
				<td><?=$entry['Цена']; ?></td>
				<td><?=$entry['Сумма']; ?></td>
			</tr>
			<?php endforeach; ?>
		</table>

		<table>
			<tr>
				<td>Итого:</td>
				<td><?=$total['Сумма']; ?></td>
			</tr>
			<tr>
				<td>В том числе НДС:</td>
				<td><?=$hasVat ? $totalVat['СУММА'] : '-'; ?></td>
			</tr>
			<tr>
				<td>Всего к оплате:</td>
				<td><?=$total['Сумма']; ?></td>
			</tr>
		</table>
	</article>
</body>
<?php
}
