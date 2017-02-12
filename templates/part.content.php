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
		<tr>
			<td>
				<?php p($record['owner'])?>
			</td>
			<td>
				<?php p($record['name'])?>
			</td>
			<td>
				<?php p($record['link/path'])?>
			</td>
			<td>
				<table class="internal">
				<?php foreach ($record['shareGroup'] as $index => $shareMember): ?>
					<tr>
						<td>
							<?php if(!is_null($_['groups'][$shareMember])):?>
							<a class="toggle-triger">
								<?php p($shareMember)?>
							</a>
							<ol class="toggle">
								<?php foreach ($_['groups'][$shareMember] as $member):?>
								<li>
									<?php p($member)?>
								</li>
								<?php endforeach;?>
							</ol>
							<?php else:?>
								<?php p($shareMember)?>
							<?php endif;?>
						<td>
						<td>
							<?php p($record['permissions'][$index]['read'])?>
						</td>
						<td>
							<?php p($record['permissions'][$index]['update'])?>
						</td>
						<td>
							<?php p($record['permissions'][$index]['create'])?>
						</td>
						<td>
							<?php p($record['permissions'][$index]['delete'])?>
						</td>
						<td>
							<?php p($record['permissions'][$index]['share'])?>
						</td>
					<tr>
				<?php endforeach;?>
				</table>
			</td>
		<tr>
	<?php endforeach;?>
</table>


	

</div>

