<div class="row">
	<div class="col-md-12">
		<div class="form-inline pull-right">
			<div class="form-group">
				<a href="<?php echo base_url('laporan/exportExcelLaporanPenjualanPerkriteriaProduk?dateStart='.$dateStart.'&dateEnd='.$dateEnd.'&toko='.$toko.'&tempat='.$tempat.'&customer='.$customer.'&kategori='.$kategori.'&subkategori='.$subkategori.'&subkategori2='.$subkategori2); ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export to Excel</a>
			</div>

			<div class="form-group">
				<a class="btn btn-info" onclick="printContent('area-print')"><i class="fa fa-print"></i> Print</a>
			</div>
		</div>
	</div>
</div>

<div class="row" style="margin-top: 20px;">
	<div class="col-md-12 table-responsive" style="height: 600px;" id="area-print">
		<table class="table" style="font-size: 10px;">
			<tr style="font-weight: bold;">
                <td width="4%">No</td>
                <td width="13%">SKU</td>
                <td>Nama Produk</td>
                <td>Satuan</td>
                <td align="right">Harga Beli</td>
                <td align="right">Harga Jual</td>
                <td align="right">Qty Terjual</td>
                <td align="right">Total HPP</td>
                <td align="right">Total Terjual</td>
                <td align="right">Profit</td>
            </tr>    

            <?php
                        $i = 1;
                        $qty_terjual = 0;
                        $total_hpp = 0;
                        $total_terjual = 0;
                        $profit = 0;

                        $count = $laporan->num_rows();

                        if($count > 0){

                        foreach($laporan->result() as $row){
                      ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row->id_produk; ?></td>
                        <td><?php echo $row->nama_produk; ?></td> 
                        <td><?php echo $row->satuan; ?></td>
                        <td align="right"><?php echo number_format($row->harga_beli,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->harga_jual,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->qty_terjual,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->harga_beli*$row->qty_terjual,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->harga_jual*$row->qty_terjual,'0',',','.'); ?></td>
                        <td align="right">
                          <?php
                            echo number_format(($row->harga_jual*$row->qty_terjual)-($row->harga_beli*$row->qty_terjual),'0',',','.');
                          ?>
                        </td>
                      </tr>
                      <?php 
                        $qty_terjual    = $qty_terjual + $row->qty_terjual; 
                        $total_hpp      = $total_hpp+($row->harga_beli*$row->qty_terjual);
                        $total_terjual  = $total_terjual+($row->harga_jual*$row->qty_terjual);
                        $profit         = $profit+(($row->harga_jual*$row->qty_terjual)-($row->harga_beli*$row->qty_terjual));
                        $i++; 
                        } 
                      ?>
                      

                      <tr style="font-weight: bold;">
                        <td colspan="6" align="center">TOTAL</td>
                        <td align="right"><?php echo number_format($qty_terjual,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($total_hpp,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($total_terjual,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($profit,'0',',','.'); ?></td>
                      </tr>

                    <?php } else { ?>
                        <tr>
                          <td colspan="10" align="center">--BELUM ADA DATA UNTUK DITAMPILKAN--</td>
                        </tr>
                    <?php } ?>
		</table>
	</div>
</div>

<script type="text/javascript">
	$(".table-responsive").niceScroll();
</script>