<?php

function json_encode($a, $indent = 1)
{
	$j = array();

	if (_keys_are_int($a)) $j[] = _implode($a); // TODO: burası $a değerlerinin içerisinde array gelirse patlar, düzeltmek lazım.
	else {
		foreach ($a as $k => $v) {
			if (is_array($v)) {
				$v_ = $v;
				$v  = json_encode($v_, $indent + 1);
				$v  = "[" .
				      substr(trim($v), 1, -1) . "]";
			} else $v = "\"$v\"";
			$j[] = "\"$k\" : $v";
		}
	}

	return str_repeat(TAB, $indent - 1) .
	       "{" . CR_LF .
	       str_repeat(TAB, $indent) .
	       implode("," . CR_LF . str_repeat(TAB, $indent), $j) . CR_LF .
	       str_repeat(TAB, $indent - 1) . "}";
}

function _keys_are_int($a)
{
	if (empty($a) || !is_array($a)) return false;
	foreach (array_keys($a) as $k) if (gettype($k) != "integer") return false;

	return true;
}

function object2array($o)
{
	if (is_object($o)) $o = (array) $o;
	if (is_array($o)) {
		$new = array();
		foreach ($o as $k => $v) {
			$new[$k] = object2array($v);
		}
	} else $new = $o;

	return $new;
}

function _implode($a)
{
	$s = "";
	foreach ($a as $k => $v) {
		if (is_array($v)) $s .= json_encode($v) . "," . CR_LF;
		else $s .= "\"" . $v . "\"," . CR_LF;
	}

	return substr(rtrim($s), 0, -1);
}
