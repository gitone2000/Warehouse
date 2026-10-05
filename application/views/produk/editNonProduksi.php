							<form style="padding: 10px;">
							    <?php
							    	foreach($produk as $row){
							    ?>
							    <div class="row">
								    <div class="col-md-6">
									    <div class="form-group">
											<label>SKU</label> <label id="skuAlert" style="color:red;"></label> 
											<input type="text" id="sku" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" value="<?php echo $sku; ?>" readonly/>
											<div></div>
										</div>
									</div>

									<div class="col-md-6">
									    <div class="form-group">
											<label>Nama Produk</label> <label id="namaProdukAlert" style="color:red;"></label> 
											<input type="text" id="namaProduk" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" value="<?php echo $row->nama_produk; ?>">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Harga Beli</label>
											<input type="text" id="hargaBeli" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" value="<?php echo $row->harga_beli; ?>">
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label>Satuan</label> <label id="satuanAlert" style="color:red;"></label> 
											<select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="satuan" required>
												<option value="">--Pilih Satuan--</option>
												<?php
													foreach($satuan->result() as $st){
												?>
												<option value="<?php echo $st->satuan; ?>" <?php if($row->satuan == $st->satuan){echo"selected";} ?>><?php echo $st->satuan; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Tempat</label>
											<select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="tempat" required="">
												<?php
													foreach($stand as $dt){
												?>
												<option value="<?php echo $dt->id_stand; ?>" <?php if($dt->id_stand==$row->tempat){echo"selected";} ?>><?php echo $dt->stand; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label>Status</label>
											<select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="status" required="">
												<option value="1" <?php if($row->status==1){echo "selected";} ?>>Aktif</option>
												<option value="0" <?php if($row->status==0){echo "selected";} ?>>Non Aktif</option>
											</select>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12 col-md-12">
								        <div class="panel panel-color panel-primary">
								            <div class="panel-heading"> 
								                <h3 class="panel-title">Harga Jual</h3> 
								            </div> 
								                            
								            <div class="panel-body"> 
								                <?php
								                	foreach($store as $str){
								                		$idStore = $str->id_store;

								                		$getPrice = $this->modelProduk->getPrice($idStore,$sku);
								                ?>
									                <div class="col-md-3">
														<div class="form-group">
															<label><?php echo $str->store; ?></label>
															<input type="text" id="hargaJual" data-id_store="<?php echo $str->id_store;?>" value="<?php echo $getPrice; ?>" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;">
														</div>
													</div>
												<?php } ?>
								            </div> 
								        </div>
								    </div>
								</div>

							    <div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label>Kategori</label> <label id="kategoriAlert" style="color:red;"></label> 
											<select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="kategori">
												<option value="">--Pilih Kategori--</option>
												<?php 
													foreach($show_kategori->result() as $kt){
												?>
												<option value="<?php echo $kt->id_kategori; ?>" <?php if($kt->id_kategori==$row->id_kategori){echo "selected";} ?>><?php echo $kt->kategori; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group" id="sub_kategori">
											<label>Subkategori</label>
											<select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="subkategori_2">
												<option value="">--Pilih Subkategori--</option>
												<?php
													$id_kategori = $row->id_kategori;
													$show_sub = $this->db->get_where("ap_kategori_1",array("id_kategori" => $id_kategori));
													foreach($show_sub->result() as $kt1){
												?>
												<option value="<?php echo $kt1->id; ?>" <?php if($kt1->id==$row->id_subkategori){echo"selected";}?>><?php echo $kt1->kategori_level_1; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group" id="sub_kategori_2">
											<label>Subkategori</label>
											<select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="subkategori_3">
												<option value="">--Pilih Subkategori--</option>
												<?php
												$id_kategori_2 = $row->id_subkategori;
												$show_sub2 = $this->db->get_where("ap_kategori_2",array("id_kategori_1" => $id_kategori_2));
												foreach($show_sub2->result() as $kt2){
												?>
												<option value="<?php echo $kt2->id; ?>" <?php if($kt2->id==$row->id_subkategori_2){echo "selected";}?>><?php echo $kt2->kategori_3; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>

									<div class="col-md-12">
										<div class="form-group">
											<a id="editProdukSql" class="btn btn-primary">Submit</a>
										</div>
									</div>
								</div>

								<?php } ?>
							</form>


<script type="text/javascript">
	var spinner = "<?php echo base_url('produk/spinner'); ?>";

	$('#kategori').change(function(){
        kategori = $('#kategori').val();
        url = "<?php echo base_url('produk/get_subkategori'); ?>";

        $('#sub_kategori').load(url,{id_kategori : kategori});

        $('#sub_kategori_2').empty();
    });

	$('#editProdukSql').on("click",function(){
                var sku             = $('#sku').val();
                var namaProduk      = $('#namaProduk').val();
                var hargaBeli       = $('#hargaBeli').val();
                var satuan          = $('#satuan').val();
                var status 			= $('#status').val();
                var tempat          = $('#tempat').val();
                var kategori        = $('#kategori').val();
                var kategori2       = $('#subkategori_2').val();
                var kategori3       = $('#subkategori_3').val();

                jsonObj = [];

                $('input[id=hargaJual]').each(function(){
                    var hargaJual   = $(this).val();
                    var idStore     = $(this).data('id_store');

                    item = {};

                    item['hargaJual'] = hargaJual;
                    item['idStore']   = idStore;

                    jsonObj.push(item);
                });

                if(sku=='' && namaProduk=='' && satuan=='' && kategori==''){
                    if(sku==''){
                        $('#skuAlert').text("*SKU Required");
                    }

                    if(namaProduk==''){
                        $('#namaProdukAlert').text('*Nama Produk Required');
                    }

                    if(satuan==''){
                        $('#satuanAlert').text('*Satuan Required');
                    }

                    if(kategori==''){
                        $('#kategoriAlert').text("*Kategori Required");
                    }
                } else {
                    $.ajax({
                                method      : "POST",
                                url         : "<?php echo base_url('produk/editProdukNonProduksiSQL'); ?>",
                                data        : {hargaJual : JSON.stringify(jsonObj),sku : sku, namaProduk : namaProduk, hargaBeli : hargaBeli, satuan : satuan, status : status, tempat : tempat, kategori : kategori, kategori2 : kategori2, kategori3 : kategori3},
                                beforeSend  : function(){
                                                $('#editProdukForm').load(spinner);
                                              }
                          }).done(function(){
                                urlForm = "<?php echo base_url('produk/formEditNonProduksi'); ?>";
                                $('#editProdukForm').load(urlForm,{sku : sku});

                                $.Notification.notify('success', 'top right', 'Edit Produk', 'Produk Berhasil Diubah');
                          });
                }

    });
</script>