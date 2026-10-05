<div class="wraper container-fluid">
    <div class="row">
        <div class="col-md-6" style="text-align: left;">
            <a href="<?php echo base_url('penjualan'); ?>" class="btn btn-primary btn-rounded m-b-5"><i class="fa fa-exchange"></i> Transaksi</a>
            <a href="<?php echo base_url('dataStok'); ?>" class="btn btn-success btn-rounded m-b-5"><i class="fa fa-cubes"></i> Data Stok</a>
        </div>
        <div class="col-md-12" style="text-align: right;">
            <a class="btn btn-default" id="hari">Hari</a> <a class="btn btn-default" id="bulan">Bulan</a> <a class="btn btn-default" id="tahun">Tahun</a>
        </div>

        <div class="col-md-12" style="margin-top: 10px;">
            <table width="100%">
                <tr>
                    <td align="right" id="filter"></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row" style="margin-top: 15px;">
        <div class="col-md-3 col-sm-6">
            <div class="widget-panel widget-style-1 bg-pink">
                <i class="fa fa-dollar"></i>
                <h2 class="m-0 counter text-white" id="sales"></h2>
                <div class="text-white">Total Penjualan</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="widget-panel widget-style-1 bg-info">
                <i class="fa fa-shopping-cart"></i>
                <h2 class="m-0 counter text-white" id="basketSize"></h2>
                <div class="text-white">Basket Size</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="widget-panel widget-style-1 bg-warning">
                <i class="fa fa-send-o"></i>
                <h2 class="m-0 counter text-white" id="transaksi"></h2>
                <div class="text-white">Transaksi</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="widget-panel widget-style-1 bg-success">
                <i class="fa fa-barcode"></i>
                <h2 class="m-0 counter text-white" id="totalItem"></h2>
                <div class="text-white">Total Item</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="portlet">
                <!-- /primary heading -->
                <div class="portlet-heading">
                    <h3 class="portlet-title text-dark text-uppercase">
                        Penjualan Perjam
                    </h3>

                    <div class="portlet-widgets">
                        <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                        <span class="divider"></span>
                        <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                        <span class="divider"></span>
                        <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                    </div>
                    <div class="clearfix"></div>
                    <div id="portlet2" class="panel-collapse collapse in">
                        <div class="portlet-body" id="salesPerHour">

                        </div>
                    </div>
                </div> <!-- /Portlet -->

            </div>
        </div> <!-- /Portlet -->


        <div class="col-lg-6">
            <div class="portlet">
                <!-- /primary heading -->
                <div class="portlet-heading">
                    <h3 class="portlet-title text-dark text-uppercase">
                        Penjualan Perkategori
                    </h3>

                    <div class="portlet-widgets">
                        <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                        <span class="divider"></span>
                        <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                        <span class="divider"></span>
                        <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                    </div>
                    <div class="clearfix"></div>
                    <div id="portlet2" class="panel-collapse collapse in">
                        <div class="portlet-body" id="salesPerkategori">

                        </div>
                    </div>
                </div>
            </div> <!-- /Portlet -->
        </div>

        <div class="col-lg-6">
            <div class="portlet">
                <!-- /primary heading -->
                <div class="portlet-heading">
                    <h3 class="portlet-title text-dark text-uppercase">
                        Penjualan Perkasir
                    </h3>

                    <div class="portlet-widgets">
                        <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                        <span class="divider"></span>
                        <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                        <span class="divider"></span>
                        <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                    </div>
                    <div class="clearfix"></div>
                    <div id="portlet2" class="panel-collapse collapse in">
                        <div class="portlet-body" id="salesPerKasir">

                        </div>
                    </div>
                </div>
            </div> <!-- /Portlet -->
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="portlet">
                <!-- /primary heading -->
                <div class="portlet-heading">
                    <h3 class="portlet-title text-dark text-uppercase">
                        Lini Masa
                    </h3>

                    <div class="portlet-widgets">
                        <div class="row">
                            <div class="col-md-12">
                                <a class="btn btn-success" id="hariLiniMasa">Hari</a> <a class="btn btn-success" id="bulanLiniMasa">Bulan</a> <a class="btn btn-success" id="tahunLiniMasa">Tahun</a>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 5px;">
                            <div class="col-md-12" style="text-align: right;" id="dateRange">
                            </div>
                        </div>

                    </div>
                    <div class="clearfix"></div>
                    <div id="portlet2" class="panel-collapse collapse in">
                        <div class="portlet-body" id="graph">

                        </div>
                    </div>
                </div> <!-- /Portlet -->

            </div>
        </div> <!-- /Portlet -->
    </div>
</div>