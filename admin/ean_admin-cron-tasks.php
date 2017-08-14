<?php
/*
This below code is added from Core Control Plugin 
Author - Dion Hulse 
Author URI: http://dd32.id.au/
*/

class core_control_cron {

	function the_page() {

		echo '<div class="wrap">';
		echo '<h2>WordPress Cron Tasks</h2>';
		echo '<div style="margin-left: 2em; margin-bottom: 3em; margin-top: 2em">';

		$crons = get_option('cron', array());
		$schedules = wp_get_schedules();
	
		echo '<table class="widefat">';
		echo '<col style="text-align: left"/>
			  <col width="10%" />
			  <col style="text-align:left" />
			  <col />
			  <col />
			  <col />
			  <thead>
			  <tr>
			  	<th>Task Type</th>
			  	<th>Due Time</th>
				<th>Hook to run</th>
				<th>Arguements</th>
				<th></th>
			  </tr>
			  </thead>
			  <tbody>
			  ';

		foreach ( (array)$crons as $time => $cron ) {
			if ( 'version' == $time ) continue;
			foreach ( (array)$cron as $hook => $task ) {
				foreach ( (array)$task as $id => $details ) {
					$once = false === $details['schedule'];
					
					echo '<tr>';
						echo '<th style="text-shadow: none !important;">',
							$once ? 'Once Off' : 'Reoccurring Task<br/> ' . (isset($schedules[$details['schedule']]) ? $schedules[$details['schedule']]['display'] : '<em><small>' . $details['schedule'] . '</small></em>'),
							'</th>';
						echo '<td>';
						//Ugly i know, I'll replace it at some point when i work out what i've done to deserve this..
						echo gmdate( 'Y-m-d H:i:s', $time + get_option( 'gmt_offset' ) * 3600 );
						echo ' ';
						echo get_option( 'gmt_offset' ) > 0 ? '+' : '-';
						if ( $pos = strpos(get_option( 'gmt_offset' ), '.') )
							echo (int)get_option( 'gmt_offset' ) . 60 * (float)( '0.' . substr(get_option( 'gmt_offset' ), $pos+1) );
						else
							echo get_option( 'gmt_offset' ) * 100;
						echo '</td>';
						echo '<td>' . $hook;
						if ( isset($GLOBALS['wp_filter'][$hook]) ) {
							$functions = array();
							foreach ( (array)$GLOBALS['wp_filter'][$hook] as $priority => $function ) {
								foreach ( $function as $hook_details )
									$functions[] = (isset($hook_details['class']) ? $hook_details['class'] . '::' : '') . $hook_details['function'] . '()';
							}
							echo '<br/><strong>Hooked functions:</strong> ' . implode(', ', $functions);
						}
						echo '</td>';
						echo '<td>';
						if ( !empty($details['args']) )
							echo implode(', ', $details['args']);
						else
							echo '<em>No Args</em>';
						echo '</td>';
						echo '<td></td>';
					echo '</tr>';
					} //end task
			} //end cron
		} //end crons
		echo '</tbody></table>';
		

		echo '</div>';
	echo '</div>';
	}
}

core_control_cron::the_page();