<div class="wrap">
			<h1 class="wp-heading-inline">AddOns</h1>
			<hr class="wp-header-end">
			<div class="theme-browser rendered">
				<div class="themes wp-clearfix">
					<?php

						$ch = curl_init();
						$timeout = 5;
						curl_setopt($ch,CURLOPT_URL,"https://infotheme.net/wp-backend-view-modules/");
						curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
						curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
						$data = curl_exec($ch);
						curl_close($ch);
						print_r($data);

					?>
			
	</div>
</div>	
</div>	