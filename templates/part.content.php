<p>Znaleziono tyle plików w tej chmurze: <?php p(count($_['data'])); ?></p>

<div id="report-table">
<table>
	<tr>
		<th>
			Użytkownik
		</th>
		<th>
			Nazwa pliku
		</th>
		<th>
			Ścieżka do pliku
		</th>
		<th>
			Link
		</th>
		<th>
			Komu udostępnia : /odczyt/zapis/tworzenie/usuwanie/udostępnianie
		</th>
	</tr>
	<?php foreach ($_['data'] as $record):?>
		<tr>
			<td>
				<?php p($record['owner'])?>
			</td>
			<td>
				<?php p($record['name'])?>
			</td>
			<td>
				<a href="<?php p($record['link/path']['link'])?>">
					<?php p($record['link/path']['path'])?> 
				</a>
			</td>
			<td>
			<?php if(!is_null($record['publicLink'])):?>
				<a href="<?php p($record['publicLink'])?>">
					Link
				</a>
			<?php else:?>
					---
			<?php endif;?>
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

