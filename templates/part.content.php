<p>Hello World <?php p(count($_['data'])); ?></p>

<p><button id="hello">click me</button></p>

<p><textarea id="echo-content">
	Send this as ajax
</textarea></p>
<p><button id="echo">Send ajax request</button></p>

Ajax response: <div id="echo-result"></div>

<div id="report-table">
<table>
	<?php foreach ($_['data'] as $record):?>
	
	<tr class="record">
		<?php foreach ($record as $param):?>
		<td class="cell">
			<?php p($param)?>
		</td>
		<?php endforeach;?>
	</tr>
	<?php endforeach;?>
</table>

<table>
	<?php foreach ($_['data'] as $record):?>
	
	<tr class="record">
		
		<td class="cell">
			<?php p($record)?>
		</td>
		
	</tr>
	<?php endforeach;?>
</table>
	

</div>

