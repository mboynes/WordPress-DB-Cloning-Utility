<?php
$debug = true;
require_once(dirname(__FILE__).'/config.php');
?>
<?php include('header.php'); ?>
	<h1>WordPress Database Import</h1>
	<form action="import.php" method="post">
		<p>
			<label for="host">WordPress Path (e.g. localhost/wordpress or 127.0.0.1/wordpress)</label><br />
			<input type="text" name="host" value="<?php echo $_SERVER['HTTP_HOST'] ?>" id="host" /> <small class="alt">(No trailing slash)</small>
		</p>
		<p>
			<label for="table_prefix">New table prefix (leave blank for no change)</label><br />
			<input type="text" name="table_prefix" value="" id="table_prefix" /> <small class="alt">(e.g. wp_)</small>
		</p>
		<p>
			<label for="blog_id">Blog to clone</label><br />
			<select name="blog_id" id="blog_id">
				<option value="">Select your site</option>
				
				<?php
				$b = new Benchmark();
				$wpdb = new db(WORDPRESS_DB_SERVER, WORDPRESS_DB_USER, WORDPRESS_DB_PASS, WORDPRESS_DB_NAME);     
				$blogs = $wpdb->array_query('SELECT `blog_id`,`domain` FROM `wp_blogs` ORDER BY `blog_id` ASC');

				foreach ($blogs as $blog)
					echo "\n".'<option value="'.$blog['blog_id'].'">'.$blog['blog_id'].' &ndash; '.$blog['domain'].'</option>';
				?>
			</select>
		</p>
		<p><input type="submit" value="Import database tables &raquo;" /></p>
	</form>
	
	<div class="dbinfo">
		<?php $wpdb->info_dump(); ?>
	</div>
	
<?php include('footer.php'); ?>