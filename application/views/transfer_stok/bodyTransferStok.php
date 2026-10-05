<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-rocket"></i> Transfer Stok</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
		      <div class="row">
                <div class="col-md-12">
                    Transfer Dari :
                </div>
              </div>

              <div class="row">
                <?php 
                    foreach($store as $st){
                ?>
                <div class="col-md-3">
                    <a href="<?php echo base_url('transferStok/formTransfer?idStore='.$st->id_store); ?>">
                        <div class="mini-stat clearfix" style="box-shadow: 1px 1px 1px #ccc;">
                            <span class="mini-stat-icon bg-info"><i class="fa fa-home"></i></span>
                            <div class="mini-stat-info text-right" style="font-size: 15px;vertical-align: middle;">
                                <?php echo $st->store; ?>
                            </div>
                        </div>
                    </a>
                </div>
                <?php } ?>
              </div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

