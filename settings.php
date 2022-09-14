<?php
$settings = \models\Settings::loadAllModule();

foreach ($settings as $key => $value) {
	define($value->code, $value->is_active ? $value->value : null);
}