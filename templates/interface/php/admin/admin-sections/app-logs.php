<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>


<div class='full_width_wrapper'>
	
				<h3 class='align_center'>App Logs</h3>
				
	
	
		<p>Error / debugging logs will automatically display here, if they exist (primary error log always shows, even if empty). <span class='bitcoin'>All log timestamps are UTC time</span> (Coordinated Universal Time). </p>
		
		<p><span class='bitcoin'>Current UTC time:</span> <span class='utc_timestamp red'></span></p>
		
		<p class='bitcoin'>Log format: </p>
		
	   <!-- Looks good highlighted as: less, yaml  -->
	   <pre class='rounded' style='display: inline-block;'><code class='hide-x-scroll less' style='white-space: nowrap; width: auto; display: inline-block;'>[UTC timestamp] runtime_mode => error_type: error_message; tracing [ (if log verbosity set to verbose) ]</code></pre>
	
	
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> Error Log </legend>
	        
	        <p>
	        
	        <b>Extra Spacing:</b> <input type='checkbox' id='errors_log_space' value='1' onchange="system_logs('errors_log');" />
	        
	        &nbsp; <b>Maximum lines:</b> <input type='text' id='errors_log_lines' value='100' maxlength="4" size="4" />
	        
	        &nbsp; <button class='force_button_style' onclick="copy_text('errors_log', 'errors_log_alert');">Copy To Clipboard</button> 
	        
	        &nbsp; <button class='force_button_style' onclick="system_logs('errors_log');">Refresh</button> 
	        
	        &nbsp; <span id='errors_log_alert' class='red'></span>
	        
	        </p>
	        
	        <!-- Looks good highlighted as: less, yaml  -->
	        <pre class='rounded'><code class='hide-x-scroll less' style='width: 100%; height: 750px;' id='errors_log'></code></pre>
			  
			  <script>
			  system_logs('errors_log');
			  </script>
		
	    </fieldset>
				
	<?php
	if ( is_readable($base_dir . '/cache/logs/smtp_errors.log') ) {
	?>
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> SMTP Error Log </legend>
	        
	        <p>
	        
	        <b>Extra Spacing:</b> <input type='checkbox' id='smtp_errors_log_space' value='1' onchange="system_logs('smtp_errors_log');" />
	        
	        &nbsp; <b>Maximum lines:</b> <input type='text' id='smtp_errors_log_lines' value='100' maxlength="4" size="4" />
	        
	        &nbsp; <button class='force_button_style' onclick="copy_text('smtp_errors_log', 'smtp_errors_log_alert');">Copy To Clipboard</button> 
	        
	        &nbsp; <button class='force_button_style' onclick="system_logs('smtp_errors_log');">Refresh</button> 
	        
	        &nbsp; <span id='smtp_errors_log_alert' class='red'></span>
	        
	        </p>
	        
	        <!-- Looks good highlighted as: less, yaml  -->
	        <pre class='rounded'><code class='hide-x-scroll less' style='width: 100%; height: 750px;' id='smtp_errors_log'></code></pre>
			  
			  <script>
			  system_logs('smtp_errors_log');
			  </script>
		
	    </fieldset>
	<?php
	}
	if ( $app_config['developer']['debug_mode'] != 'off' || is_readable($base_dir . '/cache/logs/debugging.log') ) {
	?>
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> Debugging Log </legend>
	        
	        <p>
	        
	        <b>Extra Spacing:</b> <input type='checkbox' id='debugging_log_space' value='1' onchange="system_logs('debugging_log');" />
	        
	        &nbsp; <b>Maximum lines:</b> <input type='text' id='debugging_log_lines' value='100' maxlength="4" size="4" />
	        
	        &nbsp; <button class='force_button_style' onclick="copy_text('debugging_log', 'debugging_log_alert');">Copy To Clipboard</button> 
	        
	        &nbsp; <button class='force_button_style' onclick="system_logs('debugging_log');">Refresh</button> 
	        
	        &nbsp; <span id='debugging_log_alert' class='red'></span>
	        
	        </p>
	        
	        <!-- Looks good highlighted as: less, yaml  -->
	        <pre class='rounded'><code class='hide-x-scroll less' style='width: 100%; height: 750px;' id='debugging_log'></code></pre>
			  
			  <script>
			  system_logs('debugging_log');
			  </script>
		
	    </fieldset>
	    
	<?php
	}
	?>
	    
			    
</div> <!-- full_width_wrapper END -->




		    