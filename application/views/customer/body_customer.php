<div class="wraper container-fluid">
    <div class="page-title"> 
    	<h3 class="title"><i class="fa fa-users"></i> Data Customer</h3> 
	</div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="<?php echo base_url('customer/add_customer'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add Customer</a>
                    </div>

                </div>

                <div class="row" style="margin-top: 30px;">
            		<div class="col-md-12">
            			<table class="table table-bordered" id="CSDatatables">
                            <thead>
                                <tr style="font-weight: bold;"> 
                                    <td width="5%" align="center">No</td>
                                    <td width="20%">Nama</td>
                                    <td>Kontak</td>
                                    <td>Tgl Lahir</td>
                                    <td width="10%">Tanggal Input</td>
                                    <td width="25%">Alamat</td>
                                    <td>Kategori</td>
                                    <td width="10%">Diskon (%)</td>
                                    <td width="10%">Point</td>
                                    <td width="12%"></td>
            				    </tr>
                            </thead>
            			</table>
            		</div>
            	</div>

            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
