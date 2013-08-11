<?
// WARNING: Including this file *sends* a private message.
// Set $to_user, $subject, $message, and 
// $from_userdata[username] and [user_id] globally
// Fill $from_userdata using dump_phpbb_userdata.php


define('IN_PHPBB', true);
$phpbb_root_path = '/data/htdocs/phpBB2/';
include_once($phpbb_root_path . 'extension.inc');
include_once($phpbb_root_path . 'common.'.$phpEx);
include_once($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include_once($phpbb_root_path . 'includes/functions_post.'.$phpEx);

$html_entities_match = array('#&(?!(\#[0-9]+;))#', '#<#', '#>#', '#"#');
$html_entities_replace = array('&amp;', '&lt;', '&gt;', '&quot;');

//
// Parameters
//
$submit = ( isset($HTTP_POST_VARS['post']) ) ? TRUE : 0;
$submit_search = ( isset($HTTP_POST_VARS['usersubmit']) ) ? TRUE : 0;
$submit_msgdays = ( isset($HTTP_POST_VARS['submit_msgdays']) ) ? TRUE : 0;
$cancel = ( isset($HTTP_POST_VARS['cancel']) ) ? TRUE : 0;
$preview = ( isset($HTTP_POST_VARS['preview']) ) ? TRUE : 0;
$confirm = ( isset($HTTP_POST_VARS['confirm']) ) ? TRUE : 0;
$delete = ( isset($HTTP_POST_VARS['delete']) ) ? TRUE : 0;
$delete_all = ( isset($HTTP_POST_VARS['deleteall']) ) ? TRUE : 0;
$save = ( isset($HTTP_POST_VARS['save']) ) ? TRUE : 0;

$refresh = $preview || $submit_search;

// Start session management
//
// $userdata = session_pagestart($user_ip, PAGE_PRIVMSGS);
// $userdata = unserialize($supplied_userdata);
// init_userprefs($userdata);
$userdata = $from_userdata;

//
// Var definitions
//
$mode = 'post';

$error = FALSE;
$html_on = 0;
$bbcode_on = true;
$smilies_on = TRUE;
$attach_sig = 0;
$to_username = phpbb_clean_username($to_user);
$sql = "SELECT user_id, user_notify_pm, user_email, user_lang, user_active
	FROM " . USERS_TABLE . "
	WHERE username = '" . str_replace("\'", "''", $to_username) . "'
		AND user_id <> " . ANONYMOUS;
if ( !($result = $db->sql_query($sql)) )
{
	$error = TRUE;
	$error_msg = $lang['No_such_user'];
}
if (!($to_userdata = $db->sql_fetchrow($result)))
{
	$error = TRUE;
	$error_msg = $lang['No_such_user'];
}

$privmsg_subject = trim(htmlspecialchars($subject));
		if ( empty($privmsg_subject) )
		{
			$error = TRUE;
			$error_msg .= ( ( !empty($error_msg) ) ? '<br />' : '' ) . $lang['Empty_subject'];
		}

		if ( !empty($message) )
		{
			if ( !$error )
			{
				if ( $bbcode_on )
				{
					$bbcode_uid = make_bbcode_uid();
				}

				$privmsg_message = prepare_message($message, $html_on, $bbcode_on, $smilies_on, $bbcode_uid);
                                //Extended_PM_Notification MOD
                                //Clean up all BBcode UID
                                $message_text = htmlspecialchars(trim(stripslashes($message)));
                                $quote = $lang['Quote'];
                                $code = $lang['Code'];

                                //Clean up all BBcode tags
                                $bbcode_match = array('/\[quote=\&quot\;\w+\&quot\;\]/si', '/\[quote\]/si', '/\[\/quote\]/si', '/\[code\]/si', '/\[\/code\]/si', '/\[\w+\]/si', '/\[\/\w+\]/si', '/\[\w+=\w+\]/si', '/\[\/\w+=\w+\]/si','/\[\w+\]/si', '/\[\/\w+\]/si');
                                $bbcode_replace = array("\n$quote >>\n", "\n$quote >>\n","\n<< $quote\n", "\n$code >>\n","\n<< $code\n",'','','','','','');
                                $message_text = preg_replace($bbcode_match, $bbcode_replace, $message_text);

			}
		}
		else
		{
			$error = TRUE;
			$error_msg .= ( ( !empty($error_msg) ) ? '<br />' : '' ) . $lang['Empty_message'];
		}

		$msg_time = time();

		if ( $mode != 'edit' )
		{
			//
			// See if recipient is at their inbox limit
			//
			$sql = "SELECT COUNT(privmsgs_id) AS inbox_items, MIN(privmsgs_date) AS oldest_post_time
				FROM " . PRIVMSGS_TABLE . " 
				WHERE ( privmsgs_type = " . PRIVMSGS_NEW_MAIL . " 
						OR privmsgs_type = " . PRIVMSGS_READ_MAIL . "  
						OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . " ) 
					AND privmsgs_to_userid = " . $to_userdata['user_id'];
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_MESSAGE, $lang['No_such_user']);
			}

			$sql_priority = ( SQL_LAYER == 'mysql' ) ? 'LOW_PRIORITY' : '';

			if ( $inbox_info = $db->sql_fetchrow($result) )
			{
				if ($board_config['max_inbox_privmsgs'] && $inbox_info['inbox_items'] >= $board_config['max_inbox_privmsgs'])
				{
					$sql = "SELECT privmsgs_id FROM " . PRIVMSGS_TABLE . " 
						WHERE ( privmsgs_type = " . PRIVMSGS_NEW_MAIL . " 
								OR privmsgs_type = " . PRIVMSGS_READ_MAIL . " 
								OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . "  ) 
							AND privmsgs_date = " . $inbox_info['oldest_post_time'] . " 
							AND privmsgs_to_userid = " . $to_userdata['user_id'];
					if ( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not find oldest privmsgs (inbox)', '', __LINE__, __FILE__, $sql);
					}
					$old_privmsgs_id = $db->sql_fetchrow($result);
					$old_privmsgs_id = $old_privmsgs_id['privmsgs_id'];
				
					$sql = "DELETE $sql_priority FROM " . PRIVMSGS_TABLE . " 
						WHERE privmsgs_id = $old_privmsgs_id";
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not delete oldest privmsgs (inbox)'.$sql, '', __LINE__, __FILE__, $sql);
					}

					$sql = "DELETE $sql_priority FROM " . PRIVMSGS_TEXT_TABLE . " 
						WHERE privmsgs_text_id = $old_privmsgs_id";
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not delete oldest privmsgs text (inbox)', '', __LINE__, __FILE__, $sql);
					}
				}
			}

			$sql_info = "INSERT INTO " . PRIVMSGS_TABLE . " (privmsgs_type, privmsgs_subject, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_ip, privmsgs_enable_html, privmsgs_enable_bbcode, privmsgs_enable_smilies, privmsgs_attach_sig)
				VALUES (" . PRIVMSGS_NEW_MAIL . ", '" . str_replace("\'", "''", $privmsg_subject) . "', " . $userdata['user_id'] . ", " . $to_userdata['user_id'] . ", $msg_time, '$user_ip', $html_on, $bbcode_on, $smilies_on, $attach_sig)";
		}
		else
		{
			$sql_info = "UPDATE " . PRIVMSGS_TABLE . "
				SET privmsgs_type = " . PRIVMSGS_NEW_MAIL . ", privmsgs_subject = '" . str_replace("\'", "''", $privmsg_subject) . "', privmsgs_from_userid = " . $userdata['user_id'] . ", privmsgs_to_userid = " . $to_userdata['user_id'] . ", privmsgs_date = $msg_time, privmsgs_ip = '$user_ip', privmsgs_enable_html = $html_on, privmsgs_enable_bbcode = $bbcode_on, privmsgs_enable_smilies = $smilies_on, privmsgs_attach_sig = $attach_sig
				WHERE privmsgs_id = $privmsg_id";
		}

		if ( !($result = $db->sql_query($sql_info, BEGIN_TRANSACTION)) )
		{
			message_die(GENERAL_ERROR, "Could not insert/update private message sent info.", "", __LINE__, __FILE__, $sql_info);
		}

		if ( $mode != 'edit' )
		{
			$privmsg_sent_id = $db->sql_nextid();

			$sql = "INSERT INTO " . PRIVMSGS_TEXT_TABLE . " (privmsgs_text_id, privmsgs_bbcode_uid, privmsgs_text)
				VALUES ($privmsg_sent_id, '" . $bbcode_uid . "', '" . str_replace("\'", "''", $privmsg_message) . "')";
		}
		else
		{
			$sql = "UPDATE " . PRIVMSGS_TEXT_TABLE . "
				SET privmsgs_text = '" . str_replace("\'", "''", $privmsg_message) . "', privmsgs_bbcode_uid = '$bbcode_uid' 
				WHERE privmsgs_text_id = $privmsg_id";
		}

		if ( !$db->sql_query($sql, END_TRANSACTION) )
		{
			message_die(GENERAL_ERROR, "Could not insert/update private message sent text.", "", __LINE__, __FILE__, $sql);
		}

		if ( $mode != 'edit' )
		{
			//
			// Add to the users new pm counter
			//
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_new_privmsg = user_new_privmsg + 1, user_last_privmsg = " . time() . "  
				WHERE user_id = " . $to_userdata['user_id']; 
			if ( !$status = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update private message new/read status for user', '', __LINE__, __FILE__, $sql);
			}

			if ( $to_userdata['user_notify_pm'] && !empty($to_userdata['user_email']) && $to_userdata['user_active'] )
			{
				$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($board_config['script_path']));
				$script_name = ( $script_name != '' ) ? $script_name . '/privmsg.'.$phpEx : 'privmsg.'.$phpEx;
				$server_name = trim($board_config['server_name']);
				$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
				$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';

				include_once($phpbb_root_path . 'includes/emailer.'.$phpEx);
				$emailer = new emailer($board_config['smtp_delivery']);
					
				$emailer->from($board_config['board_email']);
				$emailer->replyto($board_config['board_email']);

				$emailer->use_template('privmsg_notify', $to_userdata['user_lang']);
				$emailer->email_address($to_userdata['user_email']);
				$emailer->set_subject($lang['Notification_subject']);
					
				$emailer->assign_vars(array(
                                        'USERNAME' => stripslashes($to_username),
                                        'SENDER_USERNAME' => htmlspecialchars($userdata['username']),   //Extended_PM_Notification MOD
                                        'PM_MESSAGE' => html_entity_decode($message_text), //Extended_PM_Notification MOD
					'MESSAGE_SUBJECT' => stripslashes($privmsg_subject), //Extended_Extended_PM_Notification MOD
					'SITENAME' => $board_config['sitename'],
					'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '', 

					'U_INBOX' => $server_protocol . $server_name . $server_port . $script_name . '?folder=inbox')
				);

				$emailer->send();
				$emailer->reset();
			}
		}
?>
