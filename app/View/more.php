<?php

?>
<!--NEED: Friends, Profile, Settings, Offer view as per spec-->
<div id="more_container">
	<h1>More</h1>

		<button type="submit" name="friends" onclick="window.location='/more/accounts/friends'">Friends</button>

		<button type="submit" name="profile" onclick="window.location = '/more/accounts/profile/<?=$_SESSION['userid']?>'" >My Profile</button>

		<button type="submit" name="settings" >Settings</button>
</div>

