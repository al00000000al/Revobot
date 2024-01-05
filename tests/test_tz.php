<?php
const DATE_FORMAT = 'Y-m-d H:i:s';

function _exec(string $input): string
{
    $tz = null;
    $result = null;
    if (!empty($input)) {
        $tz = trim($input);
        if (!preg_match('/^[+-]?\d+(\.\d+)?$/', $tz)) {
            return "Неправильная зона, пишите /time +5.5";
        }
        // PMC::set(getKey(), $tz);
    } else {
        // $result = PMC::get(getKey());
        $result = 3;
    }

    if (isset($result)) {
        $tz = (string)$result;
    }

    if (!$tz) {
        $tz = 'UTC';
    } else {
        $integerPart = (int)$tz;
        $fractionalPart = $tz - $integerPart;
        $tz = 'Etc/GMT' . ($integerPart > 0 ? '-' : '+') . abs($integerPart);
    }

    try {
        $dateTime = new DateTime('now', new DateTimeZone($tz));
        if ($fractionalPart != 0) {
            $minutesOffset = (int)($fractionalPart * 60);
            $dateTime->modify($minutesOffset . ' minutes');
        }
        return $dateTime->format(DATE_FORMAT) . ' ' . $input;
    } catch (Exception $e) {
        return "Ошибка при обработке временной зоны: " . $e->getMessage();
    }
}

print(_exec('+3') . "\r\n");
print(_exec('0') . "\r\n");
print(_exec('-14') . "\r\n");
print(_exec('testtest') . "\r\n");
print(_exec('+5.5') . "\r\n");
