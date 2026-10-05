<table class="table table-bordered">
	<tr style="font-weight: bold;">
		<td width="5%">No</td>
		<td>SKU</td>
		<td>Nama Produk</td>
		<td align="right">Harga</td>
		<td>Jumlah Waste</td>
		<td align="right">Total</td>
	</tr>

	<?php
		$i = 1;
		foreach($viewReport as $row){
	?>
	<tr>
		<td><?php echo $i; ?></td>
		<td><?php echo $row->id_produk; ?></td>
		<td><?php echo $row->nama_produk; ?></td>
		<td align="right"><?php echo number_format($row->harga,'0',',','.'); ?></td>
		<td><?php echo $row->qty; ?></td>
		<td align="right"><?php echo number_format($row->total,'0',',','.'); ?></td>
	</tr>
	<?php $i++; } ?>
</table>