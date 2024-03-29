<?

function abort_if_not_manager() {
    global $User;
	global $site_manager_email_addr, $code_url;

	if ( !$User->IsProjectManager()) {
		echo "
			<p>".sprintf(_("
			You are not listed as a project manager.
			Please contact the
			%ssite manager%s
			about resolving this problem."),
                "<a href='mailto:$site_manager_email_addr'>","</a>")."
			</p><p>
			".sprintf(_("Back to %shome page%s"),"<a href='$code_url/default.php'>","</a>")."
		</p>";
		theme("","footer");
		exit();
	}
}

function echo_manager_header( ) {
	global $User, $code_url;

//	$create_project_url = "$code_url/tools/project_manager/editproject.php?action=createnew";
    $create_project_url = url_for_create_project();

	echo "<center>\n";

	// ---------------------------------------

	echo "<a href='$code_url/tools/project_manager/show_image_sources.php'>"._("Image Sources Info")."</a>";
		echo "\n|\n";
		echo "<a href='$code_url/tools/project_manager/manage_image_sources.php?action=show_sources'>"._("Manage Image Sources")."</a>";
		echo "\n|\n";
		echo "<a href='$code_url/tools/project_manager/manage_image_sources.php?action=add_source'>"._("Propose a new Image Source")."</a>";
	echo "\n";

	echo "<br>\n";
	// ---------------------------------------
	// Links for everyone


    echo "<a href='$code_url/tools/project_manager/projectmgr.php?show=search_form'>"
        ._("Search for Projects")."</a>";
    echo "\n|\n";
    echo "<a href='$code_url/tools/project_manager/projectmgr.php?show=site_active'>"
        ._("Show All Active Projects")."</a>";

	echo "\n";

	// ---------------------------------------
	// Links for PMs only

	if ( $User->IsProjectManager()) {
		echo "<br>\n";
		echo _("PM links"), ": ";
		$enc_pguser = urlencode($User->Username());
		echo "<a href='$code_url/tools/project_manager/projectmgr.php"
            ."?show=search_form"
			."&amp;project_manager=$enc_pguser'>"
			._("Search your projects")."</a>"; 
		echo "\n|\n";
		echo "<a href='$code_url/tools/project_manager/projectmgr.php?show=user_active'>"._("Show Your Active Projects")."</a>";
		echo "\n|\n";
		echo "<a href='$code_url/tools/project_manager/projectmgr.php?show=user_all'>"._("Show All of Your Projects"),"</a>";
		echo "\n|\n";
		echo "<a href='$create_project_url'>"._("Create Project")."</a>";
	}
	echo "\n";

	// --------------------------

	echo "</center>";
}

