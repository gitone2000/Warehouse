<h3 style="text-align: center;">Laporan Penjualan Perkategori Produk</h3>
<h4 style="text-align: center;">Periode</h4>
<h5 style="text-align: center;"><?php echo date_format(date_create($start),'d F Y')." - ".date_format(date_create($end),'d F Y'); ?></h5>


                          <table width="100%" class="table">
                            <tr style="font-weight: bold;">
                              <td width="30%">Nama Produk</td>
                              <td align="right" width="14%">Harga</td>
                              <td align="right" width="14%">Qty</td>
                              <td align="right" width="14%">Total</td>
                              <td align="right" width="14%">Diskon</td>
                              <td align="right" width="14%">Grand Total</td>
                            </tr>

                            <?php
                              
                              $count = $sales_perkategori->num_rows();

                              if($count > 0) {

                              $diskon = 0;
                              $total = 0;
                              foreach($sales_perkategori->result() as $dt){
                            ?>
                            <tr>
                              <td><?php echo $dt->nama_produk; ?></td>
                              <td align="right"><?php echo number_format($dt->harga_jual,'0',',','.') ?></td>
                              <td align="right"><?php echo number_format($dt->qty,'0',',','.'); ?></td>
                              <td align="right"><?php echo number_format($dt->total,'0',',','.'); ?></td>
                              <td align="right"><?php echo number_format($dt->diskon,'0',',','.'); ?></td>
                              <td align="right"><?php echo number_format($dt->total-$dt->diskon,'0',',','.'); ?></td>
                            </tr>
                            <?php $total = $total+$dt->total; $diskon = $diskon+$dt->diskon; } ?>

                            <tr style="font-weight: bold;">
                              <td colspan="4" style="font-weight: bold;text-align: center;">TOTAL</td>
                              <td align="right" style="font-weight: bold;"><?php echo number_format($diskon,'0',',','.'); ?></td>
                              <td align="right" style="font-weight: bold;"><?php echo number_format($total,'0',',','.'); ?></td>
                            </tr>

                          <?php } else { ?>

                            <tr>
                              <td colspan="6" align="center">BELUM ADA DATA UNTUK DITAMPILKAN</td>
                            </tr>

                          <?php } ?>
                          </table>
