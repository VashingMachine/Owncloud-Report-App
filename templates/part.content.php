
<div id="report-content">
<p class="score-info"><?php p($l->t('Pliki: ' . $_['start_index'] . '-' . $_['end_index'] . ' z ' . $_['total_files'])); ?></p>


<div id="navigation-pages">
	
	<?php if($_['current_page'] > 1):?>
			<?php for($n = $_['current_page'] - 5, $k = 0; $n < $_['current_page'] and $k < 5; $n++, $k++):?>
				<?php if($n > 0):?>
					<a href="/index.php/apps/report/page/<?php p($n)?>"><button><?php p($n)?></button> </a>
				<?php endif;?>
			<?php endfor;?>
	<?php endif;?>
	
	<?php for($i = $_['current_page'], $k = 0; $i <= $_['possible_pages'] and $k <= 5; $i++, $k++):?>
			<?php if($i === $_['current_page']):?>
				<a class="selected" href="/index.php/apps/report/page/<?php p($i)?>"><button><?php p($i)?></button> </a>
			<?php else:?>
			<a href="/index.php/apps/report/page/<?php p($i)?>"><button><?php p($i)?></button> </a>
			<?php endif;?>
	<?php endfor;?>
	
</div>

<div id="report-table">
<table>
	<tr>
		<th>
			<?php p($l->t('Użytkownik'))?>
		</th>
		<th>
			<?php p($l->t('Nazwa pliku'))?>
		</th>
		<th>
			<?php p($l->t('Ścieżka do pliku'))?>
		</th>
		<th>
			<?php p($l->t('Link'))?>
		</th>
		<th>
			<?php p($l->t('Dostęp'))?> 
		</th>
		<th>
			<?php p($l->t('Może zobaczyć'))?>
		</th>
		<th>
			<?php p($l->t('Może edytować'))?>
		</th>
		<th>
			<?php p($l->t('Może tworzyć w(dla folderów)'))?>
		</th>
		<th>
			<?php p($l->t('Może usunąć'))?>
		</th>
		<th>
			<?php p($l->t('Może udostępnić'))?>
		</th>
	</tr>
	<?php foreach ($_['data'] as $record):
		$n = count($record['shareGroup']);
	?>
		<tr>
			<td rowspan="<?php p($n)?>">
				<?php p($record['owner'])?>
			</td>
			<td rowspan="<?php p($n)?>">
				<?php p($record['name'])?>
			</td>
			<td rowspan="<?php p($n)?>">
				<a href="<?php p($record['link/path']['link'])?>">
					<?php p($record['link/path']['path'])?> 
				</a>
			</td>
			<td rowspan="<?php p($n)?>">
			<?php if(!is_null($record['publicLink'])):?>
				<a href="<?php p($record['publicLink'])?>">
					Link
				</a>
			<?php else:?>
					---
			<?php endif;?>
			</td>
			
			
			
			<?php foreach ($record['shareGroup'] as $index => $shareMember): ?>
				<?php if($index !== 0):?>
					<tr>
				<?php endif;?>
				
				<td>
					<?php if(!is_null($_['groups'][$shareMember])):?>
						<a class="toggle-triger">
							<?php p($shareMember . " (Grupa)")?>
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
				</td>
				<td>
					<?php p($l->t($record['permissions'][$index]['read']))?>
				</td>
				<td>
					<?php p($l->t($record['permissions'][$index]['update']))?>
				</td>
				<td>
					<?php p($l->t($record['permissions'][$index]['create']))?>
				</td>
				<td>
					<?php p($l->t($record['permissions'][$index]['delete']))?>
				</td>
				<td>
					<?php p($l->t($record['permissions'][$index]['share']))?>
				</td>
				
				<?php if($index === 0):?>
					</tr>
				<?php endif;?>
				
			<?php endforeach;?>
			
			
			
		<tr>
	<?php endforeach;?>
</table>

</div>


