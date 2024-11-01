<?php	

	/*
	 * @author Lukas Berger
	 * @package WP-Mon
	 * @version 0.5.1
	*/
	class WPMonPageFileBrowser
	{

		public static $filetype = '';
		public static $filepath = '';
		public static $filename = '';
	
		public static function WPMonPageFileBrowser_Init() {		
			add_action( 'admin_footer_text', array( 'WPMonLoader', 'WPMonAdminFooter' ) );

			?>			
				<div id="wp-mon-filebrowser-page">
			
					<script type="text/javascript">
					
						function setFileName()
						{											
							document.getElementById( 'newfile' ).value = prompt( "<?php _e( 'Enter the name for the new file', 'wp-mon' ); ?>", "<?php _e( 'Filename', 'wp-mon' ); ?>" );						
							return true;
						}
						
						function clickFileUploadElement()
						{
							document.getElementById("upload-file").click();
						}
						
						function setUploadElementInfos()
						{
							var file = document.getElementById('upload-file').value;
							var fileName = file.split("\\");
							
							document.getElementById("upload-file-name").value = fileName;
						}
						
						function submitFileUpload()
						{
							document.getElementById("upload-file-form").submit();
						}
										
					</script>
					<style type="text/css">

						.file-browser-action-menu {
							float: right;
						}		
						
						.file-browser-action-menu > a > span > span {
							display: none;
						}	
						
						.file-browser-action-menu > a:hover > span > span {
							display: inline-block;
						}

					</style>

					<div class="wrap">
						<h1>
							<?php _e( 'File-Browser', 'wp-mon' ); ?>	
						</h1>
						<h1>
							<hr />
							<form action="admin.php?page=wp-mon/file-browser<?php if( isset( $_GET['dir'] ) ) echo "&dir=" . $_GET['dir']; if( isset( $_GET['file'] ) ) echo "&file=" . $_GET['file']; ?>" method="POST" onsubmit="setFileName();">																
								<input type="hidden" value="createfile" name="action" />
								<input type="hidden" value="" name="newfile" id="newfile" />
								<input type="submit" class="add-new-h2" value="<?php _e( 'Create file', 'wp-mon' ); ?>">
							</form>
							<hr />
							<form id="upload-file-form" enctype="multipart/form-data" action="admin.php?page=wp-mon/file-browser<?php if( isset( $_GET['dir'] ) ) echo "&dir=" . $_GET['dir']; if( isset( $_GET['file'] ) ) echo "&file=" . $_GET['file']; ?>" method="POST">												
								<input type="hidden" value="uploadfile" name="action" />	
								
								<input id="upload-file-name" type="text" placeholder="<?php _e( 'Choose file', 'wp-mon' ); ?>" disabled="disabled" />
								<span id="upload-file-button" class="add-new-h2" onclick="clickFileUploadElement();"><?php _e( 'Select file', 'wp-mon' ); ?></span>
								<span id="upload-file-button" class="add-new-h2" onclick="submitFileUpload();"><?php _e( 'Upload file', 'wp-mon' ); ?></span>
								
								<input name="file" id="upload-file" type="file" style="display: none;" onchange="setUploadElementInfos();" />
							</form>
							<hr />
						</h1>	
						<?php						
							if(get_option('wpmon_setup') == null || get_option('wpmon_setup') == false)
							{
								?>
								<div id='settings-error' class='update-nag'>
									WP-Mon: <?php _e("You must setup the plugin first", 'wp-mon'); ?>, <a href="./admin.php?page=wp-mon/settings"><?php _e( 'Change settings', 'wp-mon' ); ?></a>
								</div>				
								<?php
							}							
						?>
						<?php
						
							$_COLS = array( 'Name'    => __( 'Name', 'wp-mon' ), 
											'Filesize'    => __( 'Filesize', 'wp-mon' ), 
											'Type'    => __( 'Type', 'wp-mon' ), 
											'FullPath'    => __( 'Full Path', 'wp-mon' )
										  );
							$_ITEMS = array();
						
							if( isset( $_GET['action'] ) || isset( $_POST['action'] ) )
							{				
								if( isset( $_GET['dir'] ) && isset( $_GET['file'] ) ) $path = ABSPATH . $_GET['dir'] . DIRECTORY_SEPARATOR . $_GET['file'];
								if( !isset( $_GET['dir'] ) && isset( $_GET['file'] ) ) $path = ABSPATH . $_GET['file'];	
									
								if( isset( $_GET['action'] ) && $_GET['action'] == "delete" ) { 
									unlink( $path ); 
									
									?>
										<div id='file-edited' class='updated'>
											<p><?php _e("The file was deleted", 'wp-mon'); ?></p>
										</div>		
									<?php
								}  
								else if( isset( $_GET['action'] ) && $_GET['action'] == "createfile" ) 
								{
									if( isset( $_GET['dir'] ) ) file_put_contents( ABSPATH . $_GET['dir'] . DIRECTORY_SEPARATOR . $_POST['newfile'], '' );
									if( !isset( $_GET['dir'] ) ) file_put_contents( ABSPATH . $_POST['newfile'], '' );								
								}
								else if( isset( $_POST['action'] ) && $_POST['action'] == "uploadfile" ) 
								{
									$uploaddir = ABSPATH . $_GET['dir'];
									$uploadfile = $uploaddir . DIRECTORY_SEPARATOR . basename($_FILES['file']['name']);
									
									if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) 
									{
										?>
											<div id='file-uploaded' class='updated'>
												<p><?php _e("The file was uploaded", 'wp-mon'); ?></p>
											</div>		
										<?php
									} 
									else 
									{
										?>
											<div id='file-uploaded' class='error'>
												<p><?php _e("The file wasn't uploaded", 'wp-mon'); ?></p>
											</div>		
										<?php
									}
								} 
								else if( isset( $_GET['action'] ) && $_GET['action'] == "copy" ) 
								{
									?>
										<br /><hr /><br />
										<form action="admin.php?page=wp-mon/file-browser<?php if( isset( $_GET['dir'] ) ) echo "&dir=" . $_GET['dir'];  if( isset( $_GET['file'] ) ) echo "&file=" . $_GET['file']; ?>" method="POST">											
											<input type="hidden" value="copy-submit" name="action" />
											<?php _e( 'Copy file to this path: ', 'wp-mon' ); ?>
												<input type="text" placeholder="/wp-content/plugins" value="<?php if ( isset( $_GET['dir'] ) ) echo $_GET['dir']; ?>" name="new-path" />
											<input type="submit" class="add-new-h2" value="<?php _e( 'Copy file', 'wp-mon' ); ?>">
										</form>
										<br /><hr /><br />
									<?php
								} 
								else if( isset( $_POST['action'] ) && $_POST['action'] == "copy-submit" ) 
								{
									if( isset( $_GET['dir'] ) ) $path = ABSPATH . $_GET['dir'] . DIRECTORY_SEPARATOR . $_GET['file'];
									if( !isset( $_GET['dir'] ) ) $path = ABSPATH . $_GET['file'];
									
									if( isset( $_GET['dir'] ) ) $new_path = ABSPATH . $_GET['dir'] . DIRECTORY_SEPARATOR . $_POST['new-path'] . DIRECTORY_SEPARATOR . $_GET['file'];
									if( !isset( $_GET['dir'] ) ) $new_path = ABSPATH . $_POST['new-path'] . DIRECTORY_SEPARATOR . $_GET['file'];
								
									file_put_contents( $new_path, file_get_contents( $path ) );
								}  
								else if( isset( $_GET['action'] ) && $_GET['action'] == "move" ) 
								{
									?>
										<br /><hr /><br />
										<form action="admin.php?page=wp-mon/file-browser<?php if( isset( $_GET['dir'] ) ) echo "&dir=" . $_GET['dir'];  if( isset( $_GET['file'] ) ) echo "&file=" . $_GET['file']; ?>" method="POST">	
											<input type="hidden" value="move-submit" name="action" />
											
											<?php _e( 'Move file to this path: ', 'wp-mon' ); ?>
												<input type="text" placeholder="/wp-content/plugins" value="<?php if ( isset( $_GET['dir'] ) ) echo $_GET['dir']; ?>" name="new-path" />
											<input type="submit" class="add-new-h2" value="<?php _e( 'Move file', 'wp-mon' ); ?>">
										</form>
										<br /><hr /><br />
									<?php
								} 
								else if( isset( $_POST['action'] ) && $_POST['action'] == "move-submit" ) 
								{
									if( isset( $_GET['dir'] ) ) $path = ABSPATH . $_GET['dir'] . DIRECTORY_SEPARATOR . $_GET['file'];
									if( !isset( $_GET['dir'] ) ) $path = ABSPATH . $_GET['file'];
									
									if( isset( $_GET['dir'] ) ) $new_path = ABSPATH . $_GET['file'];
									if( !isset( $_GET['dir'] ) ) $new_path = ABSPATH . $_POST['new-path'] . DIRECTORY_SEPARATOR . $_GET['file'];
									
									file_put_contents( $new_path, file_get_contents( $path ) );
									unlink( $path );
								}  
								else if( isset( $_GET['action'] ) && $_GET['action'] == "rename" ) 
								{
									?>
										<br /><hr /><br />
										<form action="admin.php?page=wp-mon/file-browser<?php if( isset( $_GET['dir'] ) ) echo "&dir=" . $_GET['dir'];  if( isset( $_GET['file'] ) ) echo "&file=" . $_GET['file']; ?>" method="POST">	
											<input type="hidden" value="rename-submit" name="action" />
											
											<?php _e( 'Enter the new name of the file: ', 'wp-mon' ); ?>
												<input type="text" placeholder="new-<?php echo $_GET['file']; ?>" value="<?php if ( isset( $_GET['dir'] ) ) echo $_GET['dir']; ?>" name="new-name" />
											<input type="submit" class="add-new-h2" value="<?php _e( 'Rename file', 'wp-mon' ); ?>">
										</form>
										<br /><hr /><br />
									<?php
								} 
								else if( isset( $_POST['action'] ) && $_POST['action'] == "rename-submit" ) 
								{
									if( isset( $_GET['dir'] ) ) $path = ABSPATH . $_GET['dir'] . DIRECTORY_SEPARATOR . $_GET['file'];
									if( !isset( $_GET['dir'] ) ) $path = ABSPATH . $_GET['file'];
									
									if( isset( $_GET['dir'] ) ) $new_path = ABSPATH . $_POST['new-name'];
									if( !isset( $_GET['dir'] ) ) $new_path = ABSPATH . $_GET['dir'] . DIRECTORY_SEPARATOR . $_POST['new-name'];
									
									file_put_contents( $new_path, file_get_contents( $path ) );
									unlink( $path );
								} 
								else if( isset( $_GET['action'] ) && $_GET['action'] == "edit" ) 
								{											
											$file_ext = pathinfo($_GET['file'], PATHINFO_EXTENSION);
											$highlighter = "html";
											
											if( $file_ext == "asp" )
											{
												$highlighter = "asp";
											}
											else if( $file_ext == "cs" )
											{
												$highlighter = "csharp";
											}
											else if( $file_ext == "css" )
											{
												$highlighter = "css";
											}
											else if( $file_ext == "java" )
											{
												$highlighter = "java";
											}
											else if( $file_ext == "js" )
											{
												$highlighter = "javascript";
											}
											else if( $file_ext == "php" )
											{
												$highlighter = "php";
											}
											else if( $file_ext == "sql" )
											{
												$highlighter = "sql";
											}
											else if( $file_ext == "txt" )
											{
												$highlighter = "text";
											}
											else if( $file_ext == "vbs" || $file_ext == "vb" )
											{
												$highlighter = "vbscript";
											}
											
									?>
									
									<script src="<?php echo plugins_url( 'assets/codepress/codepress.js' , dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . "wp-mon.php" ); ?>" type="text/javascript">	
									</script>
									<script type="text/javascript">

										function setCodeToTextarea()
										{											
											document.getElementById( 'newcontent' ).value = content.getCode();							
											return true;
										}
								
									</script>
									
									<br /><hr /><br />
									<form action="admin.php?page=wp-mon/file-browser<?php if( isset( $_GET['dir'] ) ) echo "&dir=" . $_GET['dir'];  if( isset( $_GET['file'] ) ) echo "&file=" . $_GET['file']; ?>" method="POST" onsubmit="setCodeToTextarea();">
										<input type="hidden" value="" name="newcontent" id="newcontent" />
										<input type="hidden" value="edit-submit" name="action" />
										
										<textarea class="codepress <?php echo $highlighter; ?>" id="content" name="content" rows="30" style="width:100%;">
											<?php echo htmlentities( file_get_contents( $path ) ); ?>
										</textarea><br />
										<input type="submit" class="button submit" value="<?php _e( 'Submit editing' , 'wp-mon' ); ?>" />
									</form>
									<br /><hr /><br />
									<?php
								}
								else if( isset( $_POST['action'] ) && $_POST['action'] == "edit-submit" ) 
								{									
									$new_content = stripslashes( $_POST['newcontent'] );		
									file_put_contents( $path, html_entity_decode( $new_content ) );
																		
									?>
										<div id='file-edited' class='updated'>
											<p><?php _e("The file was edited", 'wp-mon'); ?></p>
										</div>		
									<?php
								}  
								else if( isset( $_GET['action'] ) && $_GET['action'] == "view" ) 
								{			
									$file = new FileMgr( ABSPATH . $_GET['dir'] . DIRECTORY_SEPARATOR . $_GET['file'] );
									
									$root = $_SERVER['DOCUMENT_ROOT'];
									$rel_path = str_replace( $root, '', $_GET['dir'] );
									
									$url = $_SERVER['SERVER_NAME'] . "/" . $rel_path . $_GET['file'];
									$mime = $file->getMimeType();
									
									?><hr /><?php
									
									if(strstr($mime, "video/"))
									{
										?>
										<video autoplay controls loop="loop" style="max-width: 100%; width: auto; height: auto"><source src="http://<?php echo $url; ?>" type="<?php echo $mime; ?>" /></video>
										<?php
									}
									else if(strstr($mime, "image/"))
									{
										?>
										<img src="<?php echo $url; ?>" style="max-width: 100%; width: auto; height: auto" />
										<?php										
									}
									else
									{
										?>
										<!-- Sorry, but this is only a view of <?php echo $_GET['file']; ?>... -->
										<textarea readonly style="width: 100%; height: 500px;"><?php echo file_get_contents( ABSPATH . DIRECTORY_SEPARATOR . $_GET['dir'] . DIRECTORY_SEPARATOR . $_GET['file'] ); ?></textarea>
										<?php										
									}
									
									?><hr /><?php
								}
							}
						
							if( isset( $_GET['dir'] ) && $_GET['dir'] != "" && $_GET['dir'] != "." )
							{
								$path = ABSPATH . str_replace( "/" , DIRECTORY_SEPARATOR, $_GET['dir'] ) . DIRECTORY_SEPARATOR;
								
								$directories = array();
								$files = array();
								$list = array();
								foreach( scandir( $path ) as $item )
								{
									if( $item != '.' && $item != ".." )
									{
										if( is_dir( $path . DIRECTORY_SEPARATOR . $item ) )
										{
											$directories[] = $item;
										}
										else
										{
											$files[] = $item;
										}
									}
								}
								
								foreach( $directories as $item ) { $list[] = $item; }
								foreach( $files as $item ) { $list[] = $item; }
								
								if( $_GET['dir'] != "." )
								{											
									$file = new WPMonFileMgr( dirname( $path ) );
											
									$name = $file->getFileName();
									$size = null;
									$type = null;
									$fullpath = $file->getFullName();
									
									$actual_link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
									$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'dir' );
									$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'action' );
									$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'newcontent' );	
									$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'file' );			
									$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'new-path' );	
																			
									$name = html_entity_decode( '<a href="' . $actual_link . "&dir=" . dirname( $_GET['dir'] ) . '">..</a>' );
											
									$_ITEMS[] = array( 'Name' => $name, 'Filesize' => $size, 'Type' => $type, 'FullPath' => $fullpath );							
								}
								
								foreach( $list as $item )
								{
									if( $item != "." && $item != ".." )
									{
										if( is_file( $path . $item ) )
										{
											$file = new WPMonFileMgr( $path . $item );
											
											$name = $file->getFileName();
											$size = $file->getSizeInKilobyte( 2 );
											$type = $file->getMimeType();
											$fullpath = $file->getFullName();
											
											$actual_link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
											$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'action' );
											$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'newcontent' );	
											$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'file' );			
											$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'new-path' );

											$menu_actions = '<span class="file-browser-action-menu">';
											$menu_actions .= '<a href="' . $actual_link . "&action=delete&file=" . $name . '"><span class="fa fa-trash"> <span>' . __( 'Delete', 'wp-mon' ) . '</span></span> </a>';
											$menu_actions .= '<a href="' . $actual_link . "&action=edit&file=" . $name . '"><span class="fa fa-pencil"> <span>' . __( 'Edit', 'wp-mon' ) . '</span></span> </a>';
											$menu_actions .= '<a href="' . $actual_link . "&action=copy&file=" . $name . '"><span class="fa fa-clipboard"> <span>' . __( 'Copy', 'wp-mon' ) . '</span></span> </a>';
											$menu_actions .= '<a href="' . $actual_link . "&action=move&file=" . $name . '"><span class="fa fa-cut"> <span>' . __( 'Move', 'wp-mon' ) . '</span></span> </a>';		
											$menu_actions .= '<a href="' . $actual_link . "&action=rename&file=" . $name . '"><span class="fa fa-italic"> <span>' . __( 'Rename', 'wp-mon' ) . '</span></span> </a>';		
											$menu_actions .= '<a href="' . $actual_link . "&action=view&file=" . $name . '"><span class="fa fa-search"> <span>' . __( 'View', 'wp-mon' ) . '</span></span> </a>';									
											$menu_actions .= '<a href="' . admin_url( 'wpmon-download.php' ) . "?type=" . $type . "&name=" . $name . "&path=" . $path . '"><span class="fa fa-download"> <span>' . __( 'Download', 'wp-mon' ) . '</span></span> </a>';
											$menu_actions .= '</span>';
																						
											?>
											<menu class="wpmon-filebrowser-contentmenu" id="filebrowser-context-<?php echo $name; ?>" type="context">
												<menu label="<?php _e( 'File-Browser', 'wp-mon' ); ?>">
													<menuitem label="<?php _e( 'Delete file', 'wp-mon' ); ?>" onclick="window.location='<?php echo $actual_link . "&action=delete&file=" . $name; ?>';"></menuitem>
													<menuitem label="<?php _e( 'Edit file', 'wp-mon' ); ?>" onclick="window.location='<?php echo $actual_link . "&action=edit&file=" . $name; ?>';"></menuitem>
													<menuitem label="<?php _e( 'Copy file', 'wp-mon' ); ?>" onclick="window.location='<?php echo $actual_link . "&action=copy&file=" . $name; ?>';"></menuitem>
													<menuitem label="<?php _e( 'Move file', 'wp-mon' ); ?>" onclick="window.location='<?php echo $actual_link . "&action=move&file=" . $name; ?>';"></menuitem>
													<menuitem label="<?php _e( 'Rename file', 'wp-mon' ); ?>" onclick="window.location='<?php echo $actual_link . "&action=rename&file=" . $name; ?>';"></menuitem>
													<menuitem label="<?php _e( 'View file', 'wp-mon' ); ?>" onclick="window.location='<?php echo $actual_link . "&action=view&file=" . $name; ?>';"></menuitem>
													<menuitem label="<?php _e( 'Download file', 'wp-mon' ); ?>" onclick="window.location='<?php echo admin_url( 'wpmon-download.php' ) . "?type=" . $type . "&name=" . $name ."&path=" . $path; ?>';"></menuitem>
												</menu>
											</menu>
											<?php
											
											if ( isset( $_POST['search'] ) && $_POST['search'] != "" )
											{
												if( stripos( $name, $_POST['search'] ) !== false || stripos( $size, $_POST['search'] ) !== false || 
													stripos( $type, $_POST['search'] ) !== false || stripos( $fullpath, $_POST['search'] ) !== false )
												{
													$_ITEMS[] = array( 'Name' => '<section class="filebrowser-contextmenu" contextmenu="filebrowser-context-' . $name . '">' . $name . "<br />" . $menu_actions . '</section>', 'Filesize' => $size, 'Type' => $type, 'FullPath' => $fullpath );	
												}
											}
											else
											{
												$_ITEMS[] = array( 'Name' => '<section class="filebrowser-contextmenu" contextmenu="filebrowser-context-' . $name . '">' . $name . "<br />" . $menu_actions . '</section>', 'Filesize' => $size, 'Type' => $type, 'FullPath' => $fullpath );											
											}						
										}
										else if( is_dir( $path . $item ) )
										{
											$file = new WPMonFileMgr( $path . $item );
											
											$s_name = $file->getFileName();
											$name = $file->getFileName();
											$size = null;
											$type = null;
											$fullpath = $file->getFullName();
											
											$actual_link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
											$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'dir' );
											$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'action' );
											$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'newcontent' );	
											$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'file' );			
											$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'new-path' );		
																				
											$name = '<a href="' . $actual_link . "&dir=" . $_GET['dir'] . '/' . $file->getFileName() . '">' . $name . '</a>';
											
											if ( isset( $_POST['search'] ) && $_POST['search'] != "" )
											{
												if( stripos( $s_name, $_POST['search'] ) !== false || stripos( $size, $_POST['search'] ) !== false || 
													stripos( $type, $_POST['search'] ) !== false || stripos( $fullpath, $_POST['search'] ) !== false )
												{
													$_ITEMS[] = array( 'Name' => $name, 'Filesize' => $size, 'Type' => $type, 'FullPath' => $fullpath );
												}
											}
											else
											{
												$_ITEMS[] = array( 'Name' => $name, 'Filesize' => $size, 'Type' => $type, 'FullPath' => $fullpath );											
											}
										}
									}
								}
							}
							else
							{
								$path = ABSPATH;
																
								$directories = array();
								$files = array();
								$list = array();
								foreach( scandir( $path ) as $item )
								{
									if( $item != '.' && $item != ".." )
									{
										if( is_dir( $path . DIRECTORY_SEPARATOR . $item ) )
										{
											$directories[] = $item;
										}
										else
										{
											$files[] = $item;
										}
									}
								}
								
								foreach( $directories as $item ) { $list[] = $item; }
								foreach( $files as $item ) { $list[] = $item; }
																				
								foreach( $list as $item )
								{
									if( $item != "." && $item != ".." )
									{
										if( is_file( $path . $item ) )
										{
											$file = new WPMonFileMgr( $path . $item );
											
											$s_name = $file->getFileName();
											$name = $file->getFileName();
											$size = $file->getSizeInKilobyte( 2 );
											$type = $file->getMimeType();
											$fullpath = $file->getFullName();
																							
											$actual_link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
											$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'action' );
											$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'newcontent' );	
											$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'file' );			
											$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'new-path' );		
											
											$menu_actions = '<span class="file-browser-action-menu">';
											$menu_actions .= '<a href="' . $actual_link . "&action=delete&file=" . $name . '"><span class="fa fa-trash"> <span>' . __( 'Delete', 'wp-mon' ) . '</span></span> </a>';
											$menu_actions .= '<a href="' . $actual_link . "&action=edit&file=" . $name . '"><span class="fa fa-pencil"> <span>' . __( 'Edit', 'wp-mon' ) . '</span></span> </a>';
											$menu_actions .= '<a href="' . $actual_link . "&action=copy&file=" . $name . '"><span class="fa fa-clipboard"> <span>' . __( 'Copy', 'wp-mon' ) . '</span></span> </a>';
											$menu_actions .= '<a href="' . $actual_link . "&action=move&file=" . $name . '"><span class="fa fa-cut"> <span>' . __( 'Move', 'wp-mon' ) . '</span></span> </a>';		
											$menu_actions .= '<a href="' . $actual_link . "&action=rename&file=" . $name . '"><span class="fa fa-italic"> <span>' . __( 'Rename', 'wp-mon' ) . '</span></span> </a>';		
											$menu_actions .= '<a href="' . $actual_link . "&action=view&file=" . $name . '"><span class="fa fa-search"> <span>' . __( 'View', 'wp-mon' ) . '</span></span> </a>';								
											$menu_actions .= '<a href="' . admin_url( 'wpmon-download.php' ) . "?type=" . $type . "&name=" . $name . "&path=" . $path . '"><span class="fa fa-download"> <span>' . __( 'Download', 'wp-mon' ) . '</span></span> </a>';
											$menu_actions .= '</span>';
											
											?>
											<menu class="wpmon-filebrowser-contentmenu" id="filebrowser-context-<?php echo $name; ?>" type="context">
												<menu label="<?php _e( 'File-Browser', 'wp-mon' ); ?>">
													<menuitem label="<?php _e( 'Delete file', 'wp-mon' ); ?>" onclick="window.location='<?php echo $actual_link . "&action=delete&file=" . $name; ?>';"></menuitem>
													<menuitem label="<?php _e( 'Edit file', 'wp-mon' ); ?>" onclick="window.location='<?php echo $actual_link . "&action=edit&file=" . $name; ?>';"></menuitem>
													<menuitem label="<?php _e( 'Copy file', 'wp-mon' ); ?>" onclick="window.location='<?php echo $actual_link . "&action=copy&file=" . $name; ?>';"></menuitem>
													<menuitem label="<?php _e( 'Move file', 'wp-mon' ); ?>" onclick="window.location='<?php echo $actual_link . "&action=move&file=" . $name; ?>';"></menuitem>
													<menuitem label="<?php _e( 'Rename file', 'wp-mon' ); ?>" onclick="window.location='<?php echo $actual_link . "&action=rename&file=" . $name; ?>';"></menuitem>
													<menuitem label="<?php _e( 'View file', 'wp-mon' ); ?>" onclick="window.location='<?php echo $actual_link . "&action=view&file=" . $name; ?>';"></menuitem>
													<menuitem label="<?php _e( 'Download file', 'wp-mon' ); ?>" onclick="window.location='<?php echo admin_url( 'wpmon-download.php' ) . "?type=" . $type . "&name=" . $name ."&path=" . $path; ?>';"></menuitem>
												</menu>
											</menu>
											<?php
											
											if ( isset( $_POST['search'] ) && $_POST['search'] != "" )
											{
												if( stripos( $name, $_POST['search'] ) !== false || stripos( $size, $_POST['search'] ) !== false || 
													stripos( $type, $_POST['search'] ) !== false || stripos( $fullpath, $_POST['search'] ) !== false )
												{
													$_ITEMS[] = array( 'Name' => '<section class="filebrowser-contextmenu" contextmenu="filebrowser-context-' . $name . '">' . $name . "<br />" . $menu_actions . '</section>', 'Filesize' => $size, 'Type' => $type, 'FullPath' => $fullpath );	
												}
											}
											else
											{
												$_ITEMS[] = array( 'Name' => '<section class="filebrowser-contextmenu" contextmenu="filebrowser-context-' . $name . '">' . $name . "<br />" . $menu_actions . '</section>', 'Filesize' => $size, 'Type' => $type, 'FullPath' => $fullpath );											
											}											
										}
										else if( is_dir( $path . DIRECTORY_SEPARATOR . $item ) )
										{
											$file = new WPMonFileMgr( $path . DIRECTORY_SEPARATOR . $item );
											
											$s_name = str_replace( '/', '', $file->getFileName() );
											$name = str_replace( '/', '', $file->getFileName() );
											$size = null;
											$type = null;
											$fullpath = $file->getFullName();
												
											$actual_link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
											$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'dir' );
											$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'action' );
											$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'newcontent' );	
											$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'file' );			
											$actual_link = WPMonPageFileBrowser::RemoveGetVar( $actual_link, 'new-path' );	
												
											$name = '<a href="' . $actual_link . "&dir=" . str_replace( '/', '', $file->getFileName() ) . '">' . $name . '</a>';
											if ( isset( $_POST['search'] ) && $_POST['search'] != "" )
											{
												if( stripos( $s_name, $_POST['search'] ) !== false || stripos( $size, $_POST['search'] ) !== false || 
													stripos( $type, $_POST['search'] ) !== false || stripos( $fullpath, $_POST['search'] ) !== false )
												{
													$_ITEMS[] = array( 'Name' => $name, 'Filesize' => $size, 'Type' => $type, 'FullPath' => $fullpath );
												}
											}
											else
											{
												$_ITEMS[] = array( 'Name' => $name, 'Filesize' => $size, 'Type' => $type, 'FullPath' => $fullpath );											
											}
										}
									}
								}
							}
						
							$list = new WPMonListTable( $_ITEMS, $_COLS );
							$list->prepare_items();
						
						?>
						<form method="POST">
							<p>								
								<label class="screen-reader-text" for="wpmon-header-search-input"><?php _e( 'Search', 'wp-mon' ); ?>:</label> 
								<input id="wpmon-header-search-input" type="text" name="search" value="<?php if( isset($_POST['search']) ) { echo $_POST['search']; } else { echo ""; } ?>" /> 
								<input id="wpmon-header-search-button" class="button" type="submit" name="" value="<?php _e( 'Search', 'wp-mon' ); ?>" />
							</p>
						</form>
						<?php
							$list->display();
						?>
						<form method="POST">
							<p>								
								<label class="screen-reader-text" for="wpmon-header-search-input"><?php _e( 'Search', 'wp-mon' ); ?>:</label> 
								<input id="wpmon-header-search-input" type="text" name="search" value="<?php if( isset($_POST['search']) ) { echo $_POST['search']; } else { echo ""; } ?>" /> 
								<input id="wpmon-header-search-button" class="button" type="submit" name="" value="<?php _e( 'Search', 'wp-mon' ); ?>" />
							</p>
						</form>
					</div>
				</div>	
			
			<?php
		}

		public static function RemoveGetVar($url, $varname) {
			list( $urlpart, $qspart ) = array_pad( explode('?', $url), 2, '' );
			parse_str( $qspart, $qsvars );
			unset( $qsvars[$varname] );
			$newqs = http_build_query( $qsvars );
			return $urlpart . '?' . $newqs;
		}
		
		public static function str_contains( $haystack, $needle )
		{
			$result = strpos( $haystack, $needle );
			return $result !== FALSE;
		}
						
		public static function GetPosition()
		{
			return 50;
		}
		
		public static function init() {
			if( get_option( 'wpmon_module_filebrowser' ) == "1" ) 
				add_submenu_page('wp-mon', __('File-Browser', 'wp-mon'), __('File-Browser', 'wp-mon'), 'administrator', "wp-mon/file-browser", array( 'WPMonPageFileBrowser', 'WPMonPageFileBrowser_Init' ) );
		}
				
	}
	
?>																																																		