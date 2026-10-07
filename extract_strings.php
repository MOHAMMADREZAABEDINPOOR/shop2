<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$iterator = new RecursiveIteratorIterator($dir);

$persianStrings = [];

foreach ($iterator as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }

    $content = file_get_contents($file->getPathname());
    
    // Match inside __('...') or __("...")
    if (preg_match_all('/__\([\'"]([^\'"]*[\x{0600}-\x{06FF}][^\'"]*)[\'"]\)/u', $content, $m)) {
        foreach ($m[1] as $s) {
            $persianStrings[trim($s)] = true;
        }
    }

    // Match raw tags containing Persian text
    if (preg_match_all('/>\s*([^<>{}]*[\x{0600}-\x{06FF}][^<>{}]*)\s*</u', $content, $m2)) {
        foreach ($m2[1] as $s2) {
            $cleaned = trim($s2);
            if ($cleaned !== '' && !str_starts_with($cleaned, '@') && !str_starts_with($cleaned, '{{')) {
                $persianStrings[$cleaned] = true;
            }
        }
    }
}

ksort($persianStrings);
file_put_contents(__DIR__ . '/persian_strings.json', json_encode(array_keys($persianStrings), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Found " . count($persianStrings) . " Persian strings.\n";
