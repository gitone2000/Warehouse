				<p style="text-align: center;">Laporan Penjualan Perkategori</p>			
				<p style="text-align: center;">Periode</p>
				<p style="text-align: center;"><?php echo $periode; ?></p>

	
				<table class="table table-bordered" style="font-size: 12px;">
                    <tr style="font-weight: bold;">
                      <td width="5%">No</td>
                      <td>Kategori</td>
                      <td style="text-align: right;">Sales</td>
                    </tr>
 
                    <?php
                      $i = 1;
                      $kategori = $this->db->get("ap_kategori")->result();
                      foreach($kategori as $row){
                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $row->kategori; ?></td>
                      <td style="text-align: right;"><u><b><?php echo number_format($this->ModelSalesByKategori->salesPerkategori($start,$end,$row->id_kategori),'0',',','.'); ?></b></u></td>
                    </tr>

                      <?php
                        $id_kategori = $row->id_kategori;

                        $level_2_kategori = $this->db->get_where("ap_kategori_1",array("id_kategori" => $id_kategori))->result();

                        foreach($level_2_kategori as $dt){
                      ?>

                        <tr>
                          <td></td>
                          <td style="padding-left: 30px;"><li><?php echo $dt->kategori_level_1; ?></li></td>
                          <td style="text-align: right;"><i><?php echo number_format($this->ModelSalesByKategori->salesPerkategori2($start,$end,$row->id_kategori,$dt->id),'0',',','.'); ?></i></td>
                        </tr>

                        <?php
                          $id_kategori_2 = $dt->id;

                          $level_3_kategori = $this->db->get_where("ap_kategori_2",array("id_kategori_1" => $id_kategori_2));

                          foreach($level_3_kategori->result() as $tg){

                        ?>
                        <tr>
                          <td></td>
                          <td style="padding-left: 50px;"><li><?php echo $tg->kategori_3; ?></li></td>
                          <td style="text-align: right;"><i><?php echo number_format($this->ModelSalesByKategori->salesPerkategori3($start,$end,$row->id_kategori,$dt->id,$tg->id),'0',',','.'); ?></i></td>
                        </tr>
                        <?php } ?>
                      <?php } ?>

                    <?php $i++; } ?>
                  </table>