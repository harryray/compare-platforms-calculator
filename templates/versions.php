<table class="platform-data version-table">
	<thead>
		<tr>
			<th><?php _e('Version ID', 'cplat') ?></th>
			<th><?php _e('Date Created', 'cplat') ?></th>
			<th><?php _e('Active From', 'cplat') ?></th>
			<th><?php _e('Active To', 'cplat') ?></th>
			<th><?php _e('Currently Active', 'cplat') ?></th>
			<th><?php _e('Duplicate', 'cplat') ?></th>
			<th><?php _e('Remove', 'cplat') ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $all_data as $version_id => $platform_data ) : ?>
		<tr>
			<td><?php echo $version_id; ?></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td><a href="#"><?php _e('Duplicate', 'cplat') ?></a></td>
			<td><a href="#"><?php _e('Delete', 'cplat'); ?></a></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>