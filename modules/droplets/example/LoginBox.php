//:Puts a Login / Logout box on your page.
//:Use: [[LoginBox]]. Remember to enable frontend login in your website settings.
global $wb, $TEXT, $MENU;
$return_value = " ";
if(FRONTEND_LOGIN == 'enabled' && VISIBILITY != 'private' && $wb->get_session('USER_ID') == '') {
	$return_value  = '<form name="login" action="'.LOGIN_URL.'" method="post" class="login_table">';
	$return_value .= '<h2>'.$TEXT['LOGIN'].'</h2>';
	$return_value .= $TEXT['USERNAME'].':<input type="text" name="username" style="text-transform: lowercase;" /><br />';
	$return_value .= $TEXT['PASSWORD'].':<input type="password" name="password" /><br />';
	$return_value .= '<input type="submit" name="submit" value="'.$TEXT['LOGIN'].'" class="dbutton" /><br />';
	$return_value .= '<a href="'.FORGOT_URL.'">'.$TEXT['FORGOT_DETAILS'].'</a><br />';
	if(is_numeric(FRONTEND_SIGNUP))  
		$return_value .= '<a href="'.SIGNUP_URL.'">'.$TEXT['SIGNUP'].'</a>';
	$return_value .= '</form>';
} elseif(FRONTEND_LOGIN == 'enabled' && is_numeric($wb->get_session('USER_ID'))) {
	$return_value = '<form name="logout" action="'.LOGOUT_URL.'" method="post" class="login_table">';
	$return_value .= '<h2>'.$TEXT['LOGGED_IN'].'</h2>';
	$return_value .= $TEXT['WELCOME_BACK'].', '.$wb->get_display_name().'<br />';
	$return_value .= '<input type="submit" name="submit" value="'.$MENU['LOGOUT'].'" class="dbutton" /><br />';
	$return_value .= '<a href="'.PREFERENCES_URL.'">'.$MENU['PREFERENCES'].'</a><br />';
	$return_value .= '<a href="'.ADMIN_URL.'/index.php" target="_blank">'.$TEXT['ADMINISTRATION'].'</a>';
	$return_value .= '</form>';
}
return $return_value;