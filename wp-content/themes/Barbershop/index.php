<?php
	if (!defined('NXS_FRAMEWORKPATH'))
	{
		// outside context of index.php
		?>
		<html>
			<head>
				<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
			</head>
			<body>
				Index.php (Theme / Nexus framework / Not Indexed)
			</body>
		</html>		
		<?php
		return;
	}
	
	// delegate request to framework
	require_once(NXS_FRAMEWORKPATH . '/index.php');
?>