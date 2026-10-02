<?php
spl_autoload_register('magical_factor_loader_54665144sdsdsas9538hghgthgf84df5sd4dsa4fdsfsd5x');
function magical_factor_loader_54665144sdsdsas9538hghgthgf84df5sd4dsa4fdsfsd5x($class) {
    $search = 'Pargar';
    $firstOccurrencePos = strpos($class, $search);
    if ($firstOccurrencePos !== false) {
        $class = substr($class, 0, $firstOccurrencePos) . substr($class, $firstOccurrencePos + strlen($search));
    }
	$dir_class = __DIR__ . $class . ".php";
    $dir_class = str_replace('\\',"/", $dir_class );
	if ( file_exists( $dir_class ) AND is_readable( $dir_class ) ) {
		require_once $dir_class;
	}
}
