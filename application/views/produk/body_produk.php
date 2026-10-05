<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-cube"></i> Produk</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body" style="padding:30px;">
                <div class="row">
                    <div class="col-md-12" style="text-align: right;">
                        <a href="<?php echo base_url('produk/add_produk'); ?>" class="btn btn-success btn-rounded m-b-5"><i class="fa fa-plus"></i> Tambah Produk</a>
                        <a href="<?php echo base_url('produk/exportExcelProduk'); ?>" class="btn btn-primary btn-rounded m-b-5"><i class="fa fa-plus"></i> Export Produk</a>

                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-rounded m-b-5 dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Update Masal <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo base_url('produk/massUpdate/kategori'); ?>">Kategori</a></li>
                                <li><a href="<?php echo base_url('produk/massUpdate/hargaBeli'); ?>">Harga Beli</a></li>
                                <li><a href="<?php echo base_url('produk/massUpdate/hargaJual'); ?>">Harga Jual</a></li>
                            </ul>
                        </div>

                    </div>
                </div>

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <table class="table table-bordered" id="produkDatatables">
                            <thead>
                                <tr style="font-weight: bold;">
                                    <td width="5%">No</td>
                                    <td>SKU</td>
                                    <td>Nama Produk</td>
                                    <td>Satuan</td>
                                    <td>Kategori</td>
                                    <td>Status</td>
                                    <td width="5%"></td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>

