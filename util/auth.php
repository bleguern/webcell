<?php
function is_allowed($url) {

	if (isset($_SESSION['right'])) {

		for ($i = 0; $i < count($_SESSION['right']); $i++) {

			if (strtolower($url) == $_SESSION['right'][$i]) {
				return TRUE;
			}

		}
	}

	return FALSE;
}

function is_authenticated() {

	if (isset($_SESSION['authenticated'])) {

		return TRUE;
	}

 	return FALSE;
}
?>
