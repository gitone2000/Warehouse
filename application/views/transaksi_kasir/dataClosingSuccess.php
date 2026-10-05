<table width="100%">
	<tr style="font-weight: bold;">
		<td width="20%">Nama Kasir</td>
		<td width="1%">:</td>
		<td><?php echo $nama_kasir; ?></td>
	</tr>

	<tr style="font-weight: bold;">
		<td width="20%">Tanggal</td>
		<td width="1%">:</td>
		<td><?php echo date_format(date_create($tanggal),'d M Y'); ?></td>
	</tr>

	<tr style="font-weight: bold;">
		<td width="20%">Jam Closing</td>
		<td width="1%">:</td>
		<td><?php echo $jamClosing; ?></td>
	</tr>

	<tr style="font-weight: bold;">
		<td width="20%">No Closing</td>
		<td width="1%">:</td>
		<td><?php echo $noClosing; ?></td>
	</tr>
</table>
<br>
<table width="100%">
	<tr style="font-weight: bold;border-bottom: solid 1px #ccc;border-top: solid 1px #ccc;height: 40px;">
		<td width="15%">Tipe Bayar</td>
		<td align="right" width="10%">Total</td>
		<td align="right" width="10%">Diskon</td>
		<td align="right" width="10%">Retur</td>
		<td align="right" width="10%">Grand Total</td>
		<td align="right" width="10%">Nilai Closing</td>
		<td align="right" width="10%">Selisih</td>
	</tr>
	<tr style="height: 40px;">
		<?php
			$cash_value = $this->model_penjualan->cash_value($id,$tanggal);

			foreach($cash_value as $cs){
		?>

		<td style="font-weight: bold;">Cash</td>
		<td align="right"><?php echo number_format($cs->total,'0',',','.'); ?></td>
		<td align="right"><?php echo number_format($cs->diskon,'0',',','.'); ?></td>
		<td align="right">
			<?php
				$retur_value = $this->model_penjualan->retur_value($id,$tanggal);

				echo number_format($retur_value,'0',',','.')
			?>
		</td>
		<td width="20%" align="right"><?php echo number_format($cs->total-($cs->diskon + $retur_value),'0',',','.'); ?></td>
		<td width="20%" align="right">
			<?php
				$nilaiClosingCash = $this->model_penjualan->nilaiClosingCash($id,$tanggal);

				echo number_format($nilaiClosingCash,'0',',','.');
			?>
		</td>
		<td align="right">
			<?php 
				echo number_format($nilaiClosingCash-($cs->total-($cs->diskon + $retur_value)));
			?>
		</td>
		<?php } ?>
	</tr>

	<tr style="height: 40px;">
		<td colspan="7" style="font-weight: bold;">Debit</td>
	</tr>

	<?php
		foreach($list_debit as $dbt){
			$id_account = $dbt->id_payment_account;

			$debit_value = $this->model_penjualan->debit_value($id_account,$id,$tanggal);
			
			$debitValueClosing = $this->model_penjualan->debitValueClosing($id_account,$id,$tanggal);

			foreach($debit_value as $dv){
	?>

	<tr style="height: 40px;">
		<td style="padding-left: 10px;">Debit <?php echo $dbt->account;?></td>
		<td align="right"><?php echo number_format($dv->total,'0',',','.'); ?></td>
		<td align="right"><?php echo number_format($dv->diskon); ?></td>
		<td align="right">-</td>
		<td align="right"><?php echo number_format($dv->total-$dv->diskon,'0',',','.'); ?></td>
		<td align="right" >
			<?php echo number_format($debitValueClosing,'0',',','.'); ?>
		</td>
		<td align="right">
			<?php 
				echo number_format($debitValueClosing-($dv->total-$dv->diskon),'0',',','.')
			?>
		</td>
	</tr>

	<?php 
		}
			} 
	?>

	<tr style="height: 40px;">
		<td colspan="7" style="font-weight: bold;">Kredit</td>
	</tr>

	<?php
		foreach($list_kredit as $krd){
	
		$id_account_kr = $krd->id_payment_account;

		$kredit_value = $this->model_penjualan->kredit_value($id_account_kr,$id,$tanggal);

		$kreditValueClosing = $this->model_penjualan->kreditValueClosing($id_account_kr,$id,$tanggal);

		foreach($kredit_value as $krv){
	?>
	<tr style="height: 40px;">
		<td style="padding-left: 10px;">Kredit <?php echo $krd->account;?></td>
		<td align="right"><?php echo number_format($krv->total); ?></td>
		<td align="right"><?php echo number_format($krv->diskon); ?></td>
		<td align="right" align="right">-</td>
		<td align="right"><?php echo number_format($krv->total-$krv->diskon,'0',',','.'); ?></td>
		<td align="right" >
			<?php echo number_format($kreditValueClosing,'0',',','.'); ?>
		</td>
		<td align="right">
			<?php 
				echo number_format($kreditValueClosing-($krv->total-$krv->diskon),'0',',','.'); 
			?>
		</td>
	</tr>
	<?php 
		} 
			} 
	?>


	<?php
		$transfer_value = $this->model_penjualan->transfer_value($id,$tanggal);

		foreach($transfer_value as $trs){
	?>
	<tr style="height: 40px;">
		<td style="font-weight: bold;">Transfer</td>
		<td align="right"><?php echo number_format($trs->total,'0',',','.'); ?></td>
		<td align="right"><?php echo number_format($trs->diskon,'0',',','.'); ?></td>
		<td align="right">-</td>
		<td align="right"><?php echo number_format($trs->total-$trs->diskon,'0',',','.'); ?></td>
		<td align="right">
			<?php
				$nilaiClosingTransfer = $this->model_penjualan->nilaiClosingTransfer($id,$tanggal);

				echo number_format($nilaiClosingTransfer,'0',',','.');
			?>
		</td>
		<td align="right">
			<?php
				echo number_format($nilaiClosingTransfer-($trs->total-$trs->diskon),'0',',','.');
			?>
		</td>
	</tr>
	<?php } ?>
</table>	

<br>
<br>
<table width="100%">
	<tr style="font-weight: bold;height: 100px;">
		<td width="33.3%" align="center" style="vertical-align: bottom;text-decoration: underline;">Finance</td>
		<td width="33.3%" align="center" style="vertical-align: bottom;text-decoration: underline;">Head Store</td>
		<td width="33.3%" align="center" style="vertical-align: bottom;text-decoration: underline;">Kasir</td>
	</tr>
</table>
<!-- definisikan id dan tanggal untuk di kirim ke table closing id-->
<input type="hidden" id="idUser" value="<?php echo $id; ?>"/>
<input type="hidden" id="tanggal" value="<?php echo $tanggal; ?>"/>