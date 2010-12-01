<?php

if (!function_exists('is_cli')) {
	function is_cli() { # => command line interface
		return php_sapi_name() == 'cli' ? true : false;
	}
}

if (!function_exists('h')) {
	function h($s) { # => entities helper
		return is_cli() ? $s : htmlentities($s);
	}
}

if (!function_exists('link_to')) {
	function link_to($text, $a) {
		global $params, $action;
		if ($params) $p = preg_replace('/(^.*?)a=(.*?)(?:&|$)/i', "$1", $params);
		echo '<a href="stats.php?a=',$a,$p?'&':'',$p,'">', $text, '</a>';
	}
}

if (!function_exists('p')) {
	function p($s) { # => helper paragraph
		return is_cli() ? $s."\n" : tag($s);
	}
}

if (!function_exists('pre')) {
	function pre($s) {
		return is_cli() ? $s."\n" : tag($s, 'pre');
	}
}

if (!function_exists('ul')) {
	function ul($arr) {
		return is_cli() ?
			'* ' . join("\n* ", $arr) . "\n"
		:
			'<ul><li>' . join('</li><li>', $arr) . '</li></ul>';
	}
}

if (!function_exists('tag')) {
	function tag($s, $t='p', $attr=array()) {
		if (is_cli()) return $s;
		$html = '';
		if (!empty($attr)) {
			foreach ($attr as $key=>$val)
				$html .= ' ' . $key. '="' . $val .'"';
		}
		return '<'.$t.$html.'>'.$s.'</'.$t.'>';
	}
}

if (!function_exists('sql')) {
	function sql($s) { # => escape string for sql
		return mysql_real_escape_string($s);
	}
}

if (!function_exists('humanize')) {
	function humanize($s) {
		return str_replace('_',' ', $s);
	}
}

if (!function_exists('titleize')) {
	function titleize($s) {
		return ucwords(humanize($s));
	}
}

if (!function_exists('tabulate')) {
	function tabulate( $arr = false, $options = array() ) {
		# => This takes a 2d array and turns it into a table!
		$output = array();
		$options['humanize_headers'] = isset($options['humanize_headers']) ? $options['humanize_headers'] : true;
		$options['data_filters'] = isset($options['data_filters']) ? $options['data_filters'] : false;
		$options['print'] = isset($options['print']) ? $options['print'] : false;

		if ( $arr == false ) {
			$output[] = tag('No data to tabulate', 'div', array('class' => 'error'));
		}
		else {
			$first_row = 1;
			$header_row = array();
			foreach ($arr as $row) {
				$this_row = array();
				foreach ($row as $key=>$cell) {
					if ($first_row) {
						$header_row[] = tag($options['humanize_headers'] ? titleize($key) : $key, 'th');
					}
					if ($options['data_filters'] && isset($options['data_filters'][$key]))
						$cell = call_user_func($options['data_filters'][$key], $cell);
					$this_row[] = tag($cell, 'td');
				}
				if ( $first_row ) {
					$first_row = 0;
					$output[] = is_cli() ? join(' | ', $header_row) : tag(join($header_row), 'tr');
				}
				$output[] = is_cli() ? join(' | ', $this_row) : tag(join($this_row), 'tr');
			}
		}
		if ($options['print'] == false)
			return tag(join("\n", $output), 'table', array('cellpadding'=>'0', 'cellspacing'=>'0'));
		else
			echo tag(join("\n", $output), 'table', array('cellpadding'=>'0', 'cellspacing'=>'0'));
	}
}

if (!function_exists('is_selected')) {
	function is_selected($field_value, $stored_value) {
		if ($stored_value==null || $stored_value==false || $stored_value=='') return;
		elseif (is_array($stored_value))
			$is_selected = in_array($field_value, $stored_value);
		else
			$is_selected = ($field_value == $stored_value);
		echo $is_selected ? ' selected="selected"' : '';
	}
}

?>