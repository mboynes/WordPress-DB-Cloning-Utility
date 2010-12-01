<?php
if (!isset($_POST['blog_id']) || !is_numeric($_POST['blog_id'])) header('Location: index.php');
$debug = true;
$good_to_go = false;
require_once(dirname(__FILE__).'/config.php');
?>

<?php include('header.php');?>
<h1>Importing Blog <?php echo $_POST['blog_id'] ?></h1>
<?php
	$wpdb = new db(WORDPRESS_DB_SERVER, WORDPRESS_DB_USER, WORDPRESS_DB_PASS, WORDPRESS_DB_NAME);

	$blog_id = mysql_real_escape_string($_POST['blog_id']);

	$domain = $wpdb->query('SELECT `domain` FROM `wp_blogs` WHERE `blog_id`='.$blog_id.' LIMIT 1');
	if (isset($domain['domain'])) $domain = $domain['domain'];

	$file = dirname(__FILE__) . '/data/'.$blog_id.date('_YmdHis');
	$newfile = $file.'_localized.sql';
	$file .= '.sql';
	
	# Get a list of tables...
	$tables = $wpdb->array_query('SHOW TABLES LIKE "wp\_'.$blog_id.'\_%"');
	$table_list = array();

	# Crawl them and download the data
	foreach ($tables as $table) {
		$table_list[] = implode('',$table);
		// $wpdb->query('SELECT * INTO OUTFILE "'.sprintf($file_base, $table).'" FROM `'.$table.'`');
	}
	$command = PATH_TO_MYSQLDUMP.' -h '.WORDPRESS_DB_SERVER.' -u '.WORDPRESS_DB_USER.' -p'.WORDPRESS_DB_PASS.' -B '.WORDPRESS_DB_NAME.' --tables '.join(' ', $table_list).' --opt > ' . $file;
	# echo pre("Run this command in your shell:\n\n".$command."\n\n");

	// $command = 'locate mysqldump';
	system($command, $output);
	if ($output === 0) {
		$fr = fopen($file, 'r+');
		$fw = fopen($newfile, 'w');

		$search = array();
		if ($_POST['table_prefix']) {
			$search[] = 'wp_'.$blog_id.'_';
			$replace[] = $_POST['table_prefix'];
		}
		if ($_POST['host']) {
			$search[] = $domain;
			$replace[] = $_POST['host'];
		}
		if ($fr && $fw) {
			// fwrite($fw, 'USE '.LOCAL_DB_NAME.";\n\n");
			echo p('Files opened successfully! Trying to write new file and replace `'.implode('`,`', $search).'` with `'.implode('`,`', $replace).'`.');
			while (($buffer = fgets($fr)) !== false) {
				fwrite($fw, str_replace($search, $replace, $buffer));
				// echo pre(str_replace($search, $replace, $buffer));
			}
			if (!feof($fr)) {
				echo p('Error: unexpected fgets() fail');
			}
			else {
				$good_to_go = true;
			}
		}
		else echo p('Trouble opening files');
		fclose($fr);
		fclose($fw);
	}
	else {
		echo p('Something went wrong running this command (error message `'.$output.'`):'), pre($command);
	}
	
	if ($good_to_go) {
		$localdb = new db(LOCAL_DB_SERVER, LOCAL_DB_USER, LOCAL_DB_PASS, LOCAL_DB_NAME);
		$prefix = $_POST['table_prefix'] ? $_POST['table_prefix'] : 'wp_'.$blog_id.'_';
		if ($prefix != 'wp_') {
			$localdb->query('DROP TABLE IF EXISTS `'.$prefix.'users`,`'.$prefix.'usermeta`');
			$localdb->query('CREATE TABLE `'.$prefix.'users` LIKE `wp_users`');
			$localdb->query('INSERT INTO `'.$prefix.'users` SELECT * FROM `wp_users`');
			$localdb->query('CREATE TABLE `'.$prefix.'usermeta` LIKE `wp_usermeta`');
			$localdb->query('INSERT INTO `'.$prefix.'usermeta` SELECT * FROM `wp_usermeta`');
			$localdb->query('UPDATE `'.$prefix.'usermeta` SET `meta_key` = REPLACE( `meta_key` , "wp_", "'.$prefix.'" )');
		}
		system('cat '.$newfile.' | '.PATH_TO_MYSQL.' -h '.LOCAL_DB_SERVER.' -u '.LOCAL_DB_USER.' -p'.LOCAL_DB_PASS.' -C '.LOCAL_DB_NAME, $output);
		if ($output === 0)
			echo p('Database imported successfully!');
		else
			echo p('There was an error importing the database: `'.$output.'`');
	}
	?>
	<div class="dbinfo">
		<?php $wpdb->info_dump(); ?>
	</div>

<?php include('footer.php'); ?>