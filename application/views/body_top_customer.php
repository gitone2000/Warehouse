<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Top Customer</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
          <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row" style="margin-top: 30px;">
                  <div class="col-md-12">

                  	<h3 style="text-align: center;">Laporan Pembelian Terbanyak</h3>
                    <h4 style="text-align: center;">Berdasarkan Jumlah Belanja Customer</h4>
                    
                  	<table class="table table-striped" style="font-size:11px;">
                      <tr style="background: #2A303A;color:white;font-weight: bold;">
                        <td width="4%">No</td>
                        <td width="13%">Nama Customer</td>
                        <td align="right">Subtotal</td>
                        <td align="right">Diskon Channel</td>
                        <td align="right">Diskon</td>
                        <td align="right">Poin Reimburs</td>
                        <td align="right">Diskon Peritem</td>
                        <td align="right">Total</td>
                       </tr>

                       <?php
                       	if(empty($this->uri->segment(3))){
                                    $i = 0+1;
                                } else {
                                    $i = $this->uri->segment(3)+1;
                                }
                       	foreach($top_customer as $row){
                       ?>
                       <tr>
                       	<td><?php echo $i; ?></td>
                       	<td><?php echo $row->nama; ?></td>
                       	<td align="right"><?php echo number_format($row->total,'0',',','.'); ?></td>
                       	<td align="right"><?php echo number_format($row->diskon,'0',',','.'); ?></td>
                       	<td align="right"><?php echo number_format($row->diskon_free,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->poin_value,'0',',','.'); ?></td>
                       	<td align="right"><?php echo number_format($row->diskon_otomatis,'0',',','.'); ?></td>
                       	<td align="right"><?php echo number_format($row->grand_total,'0',',','.'); ?></td>
                       </tr>
                       <?php $i++; } ?>
                    </table>
                  </div>
                </div>

                <div class="row">
                	<div class="col-md-12" style="text-align: center;">
                		<?php echo $paging; ?>
                	</div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

