<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->    
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
	                <div class="col-md-12" style="text-align: right;">
	                    <a onclick="printContent('area-print')" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
	                </div>
                </div>

                <div class="row" id="area-print">
                	<div class="col-md-12">
                		<table width="100%" style="font-size: 13px;">
                            <?php
                                foreach($header->result() as $hd){
                            ?>
                            <tr>
                                <td style="text-align: center;">
                                    <?php echo $hd->nama_perusahaan; ?> <br>
                                    <u>Goods Received / Nota Penerimaan</u>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>

                        <?php
                            foreach($dataReceive as $row){
                        ?>
                        <table width="100%" style="margin-top: 5px;font-size: 12px;">
                            <tr>
                                <td width="50%">
                                    <table width="100%">
                                        <tr>
                                            <td style="font-weight:  bold;width: 30%;">No Receive</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $row->no_receive; ?></td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:  bold;">No PO</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $row->no_po; ?></td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:  bold;">Tanggal Diterima</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo date_format(date_create($row->tanggal_terima),'d F Y'); ?></td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:  bold;">Diterima Oleh</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $row->received_by; ?></td>
                                        </tr>
                                    </table>
                                </td>

                                <td style="vertical-align: top;">
                                    <table width="100%">
                                        <tr>
                                            <td style="font-weight:  bold;width: 30%;">Diperiksa Oleh</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $row->checked_by; ?></td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:  bold;">Diterima Di </td>
                                            <td style="width: 3%;">:</td>
                                            <td>
                                                <?php
                                                    if($row->diterimaDi==0){
                                                        echo "Gudang";
                                                    } else {
                                                        $namaStore = $this->model1->namaStore($row->diterimaDi);                                               
                                                        echo $namaStore;
                                                    }
                                                ?>  
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:  bold;">Supplier</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $row->supplier;?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <?php } ?>

                        <table style="margin-top: 5px;width: 100%;border:solid 1px black;font-size: 12px;">
                            <tr style="font-weight: bold;border-bottom: solid 1px black;">
                                <td width="5%" align="center" style="border-right: solid 1px black;">No</td>
                                <td width="20%" style="border-right: solid 1px black;padding-left: 1px;">SKU</td>
                                <td style="border-right: solid 1px black;padding-left: 1px;">Nama Produk</td>
                                <td width="8%" style="text-align: center;border-right: solid 1px black;">Quantity</td>
                                <td width="8%" style="text-align: center;">Satuan</td>
                           
                            </tr>

                            <?php
                                $i=1;
                                $total = 0;
                                foreach($receive_item->result() as $row){
                            ?>
                            <tr>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $i; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->id_produk; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->nama_produk; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->qty; ?></td>
                                <td style="text-align: center;"><?php echo $row->satuan; ?></td>               
                            </tr>
                            <?php $i++; } ?>
                        </table>       

                        <table width="100%">
                            <tr>
                                <td width="50%" style="text-align: center;">
                                    <center>
                                    <table width="50%">
                                        <tr>
                                            <td style="height: 50px;border-bottom: solid 1px black;text-align: center;font-size: 12px;">
                                                Penerima
                                            </td>
                                        </tr>
                                    </table>
                                    </center>
                                </td>

                                <td width="50%" style="text-align: center;">
                                    <center>
                                    <table width="50%">
                                        <tr>
                                            <td style="height: 50px;border-bottom: solid 1px black;text-align: center;font-size: 12px;">
                                                Pemeriksa
                                            </td>
                                        </tr>
                                    </table>
                                    </center>
                                </td>
                            </tr>
                        </table> 
                	</div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
