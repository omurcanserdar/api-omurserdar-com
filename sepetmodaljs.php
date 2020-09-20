<!-- SEPET MODAL -->
            <div class="modal fade" id="sepetModal" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                         <h4 class="modal-title modSepetTitle">Sepetim</h4>
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body modSepetBody">
                              
                        </div>
                   </div>
                </div>
            </div>
     <!-- SON SEPET MODAL-->
 
 <!-- SEPETIM GORUNTULE JS -->
                         <script type="text/javascript">
                          $(document).ready(function(){
                            $('.btnSepetim').on("click",function(){
                                var dsecim="btnSepetim";
                                var jc;
                                  $.ajax({
                         method: 'GET',
                         url : "https://www.api.omurserdar.com/ajaxSepetim.php",
                         data : {secim:dsecim},
                          beforeSend: function(data) {
                          //$('#yukleniyorModal').modal("show");
                          jc=$.dialog({
                                closeIcon:false,
                                icon: 'fa fa-spinner fa-spin',
                                type:'purple',
                                title: '<b class="text-info"> YÜKLENİYOR <b>',
                                content: '<i class="fas fa-spinner fa-pulse fa-4x"></i> Yükleniyor'
                                                 });
                          
                            
                        },
                         success: function(data){
                               data=JSON.parse(data);
                               jc.close();
                               if(data.sayi==0){
                                      $('.modSepetTitle').html("<h4> SEPETİM </h4>");
                                       $('.modSepetBody').html("<h2> SEPET BOŞ </h2>");
                               }
                               else{
                                     $('.modSepetTitle').html("<h4>SEPETİM <small class='text-primary'>Sipariş verebilmek için sepet tutarı en az "+data.minalim+" ₺ olmalı ! (<b>"+data.kurumsal+"</b>)</small></h4>");
                                     $('.modSepetBody').html(data.cevap);
                                    
                               }
                                       
                                       $('#sepetModal').modal("show");
                                       if(data.sayi!=0){
                                 var sepetenvDizi=[];//kaldirm click de erişeceğimden üst tanımladım
                                //unbind ile bir önceki event kaldırdım 
                                
                             $("#sepetModal").unbind("shown.bs.modal").on('shown.bs.modal', function(){
                               //$('#yukleniyorModal').modal("hide"); 
                                //$('.modSepetBody').append("1");
           
                                   
                                    var eleman=$('.modSepetBody').children("center").children(); //sepet center a eriştim
                                        eleman.each(function(i,v){
                                            if($(this).attr("sepetenvid")!=null){
                                            sepetenvDizi.push($(this).attr("sepetenvid"));
                                            }
                                        });
                                });
                                       }
                                
                                
     
                                
                                 //SİPARİS VERME
                                 $('.btnSiparisVer').click(function(){
                                     
                                        //var pt = $(".tutar").attr('id');
                                        //alert(pEnvId);
                                        var dsecim="btnSiparisVer";
                                        //var dtutar = $(".toptutar").data('id');
                                        //alert("toptutar: "+dtutar);
                                   $.ajax({
                         method: 'POST',
                         url : "https://www.api.omurserdar.com/ajaxSiparis.php",
                         //data : {secim:dsecim,toplamtutar:dtutar},
                         data : {secim:dsecim},
                         success: function(data){
                              data=JSON.parse(data);
                             if(data.cevap=="eklendi"){  
                                            var diSipKod=$.dialog({
                                type:'green',
                                title: '<b class="text-success"> S Ü P E R <b>',
                                content: '<b class="text-danger">'+data.sipKod+' </b> kodlu sipariş oluşturuldu',
                                                 });
                                            mesajKapat(diSipKod);
                                             $('.nav-pills a[href="#pills-siparislerim"]').trigger('click');
                                 $('#sepetModal').modal("hide");
                                 
                                 
                             }
                             else if(data.cevap=="minhata"){
                             var diMinHata=$.dialog({
                        type:'purple',         
                        title: ' <b class="text-warning"> UYARI <b> ',
                        content: 'Minimum alım tutarı karşılanmadı !',
                                                        });
                                           mesajKapat(diMinHata,3000);            
                             }
                                            else{
                                                $.dialog({
                        type:'red',
                        title: ' <b class="text-danger"> HATA <b> ',
                        content: 'Sipariş Oluşturulamadı :( !',
                                                        });
                                        }
                                 }
                                 });
                                        });
                                //SİPARİS VERME son
                                
                                //SEPETİMDEN KALDIR
                                $('.btnSepetEnvKaldir').on('click', function(){
                                   // alert("sepet kaldırma bitmediiii");
                                   var dEnvid=$(this).attr("data-id");
                                   var sec="btnSepetEnvKaldir";
                                   
                                  
                                   
                                     $.ajax({
                         method: 'POST',
                         url : "https://www.api.omurserdar.com/ajaxSepetim.php",
                         data : {secim:sec,envid:dEnvid},
                         success: function(sepetenvsildata){
                 sepetenvsildata=JSON.parse(sepetenvsildata);
                    if(sepetenvsildata.cevap=="silindi"){  
                        
                        /*
                         sepetenvDizi = sepetenvDizi.filter((value)=>value!=dEnvid);
                            //console.log("kaldirdim son hali: "+sepetenvDizi)
                                   
                        $("#cardsepenv"+dEnvid).slideUp(500, function() { $(this).remove();} );
                        var oncekiTopTutar=$(".toptutar").attr("id");
                        var guncelTopTutar=oncekiTopTutar-sepetenvsildata.silinenTutar;
                        $(".toptutar").attr("data-id",guncelTopTutar);//tutar data-id gncl
                        $(".toptutar").attr("id",guncelTopTutar);
                        $(".toptutar").html("tutar : "+guncelTopTutar+" &#8378;")
                            
                           var min=$(".toptutar").attr("limit");
                  

                            if(guncelTopTutar<min){
                                $(".btnSiparisVer").prop("hidden",true);
                                //$('.modSepetTitle').html("<h4> SEPETİM </h4>");
                            }
                            else{
                                  $(".btnSiparisVer").prop("hidden",false);
                            }
                            
                            if(sepetenvsildata.sepetkalanenvsayi==0){
                                $('.modSepetBody').html("<h2>SEPET BOŞ</h2>");
                                 $('.modSepetTitle').html("<h4> SEPETİM </h4>");
                            }
                        */
                        $("#sepetModal").unbind("shown.bs.modal");
                        $("#sepetModal").modal("hide");
                        $('.btnSepetim').trigger("click");
                                  }
                            }//sepetkaldir success
                                 });
                                });
                          //SEPETiMDEN KALDIR SON
                          
                          
                //attır
                          
                $('.btnAdetArttir').on('click', function(){
                   //$('.btnsepetEkle').trigger("click"); yüzlerce nesne var hangisi tetikelenecek :) :) 
                             
                             
                    var pEnvId = $(this).attr('id');
                    var pEnvFiyat=$(".efiyat"+pEnvId).data("id");
                    var dsecim="btnsepetEkle";
 
                    $.ajax({
                         method: 'POST',
                         url : "https://www.api.omurserdar.com/ajaxSepetim.php",
                         //data : {secim:dsecim,id:pEnvId,fiyat:pEnvFiyat},
                         data : {secim:dsecim,id:pEnvId},
                         success: function(eklendiMi){
                            if(eklendiMi=="arti"){
                                   
                            $("#sepetModal").unbind("shown.bs.modal");
                            $("#sepetModal").modal("hide");
                            $('.btnSepetim').trigger("click");
                                           
                            }
                            else if(eklendiMi=="limit"){
                                
                            $("#sepetModal").unbind("shown.bs.modal");
                            $("#sepetModal").modal("hide");
                                
                                var dilim=$.dialog({
                                type:'purple',         
                                title: ' <b class="text-warning"> Bilgilendirme <b> ',
                                content: 'Limit değerine ulaşıldığından işlem gerçekleştirilemedi !',
                                onClose: function () {
                                    $('.btnSepetim').trigger("click");
                                }
                                });
                                mesajKapat(dilim);  
                                
                                
                                
                            }
                                        
                            else{
                                /*
                                $.dialog({
                                title: '<b class="text-danger"><i class="fas fa-times"></i> HATA </b>',
                                content: 'Sepetine Eklenemedi :( ! '+eklendiMi,
                                    });
                                    */
                                }
                                
                        }
                    });
                              
                });
                          
               //son arttır
               
               $('.btnAdetAzalt').on('click', function(){
                   //$('.btnsepetEkle').trigger("click"); yüzlerce nesne var hangisi tetikelenecek :) :) 
                             
                             
                    var pEnvId = $(this).attr('data-id');
                    //var pEnvFiyat=$(".efiyat"+pEnvId).data("id");
                    var dsecim="btnSepetEnvAzalt";
 
                    $.ajax({
                         method: 'POST',
                         url : "https://www.api.omurserdar.com/ajaxSepetim.php",
                         //data : {secim:dsecim,id:pEnvId,fiyat:pEnvFiyat},
                         data : {secim:dsecim,envid:pEnvId},
                         success: function(resp){
                             
                            if(resp=="azaldi"){
                                   
                            $("#sepetModal").unbind("shown.bs.modal");
                            $("#sepetModal").modal("hide");
                            $('.btnSepetim').trigger("click");
                                           
                            }
                                        
                            else{
                                alert(resp);
                                /*
                                $.dialog({
                                title: '<b class="text-danger"><i class="fas fa-times"></i> HATA </b>',
                                content: 'Sepetine Eklenemedi :( ! '+eklendiMi,
                                    });
                                    */
                                }
                                
                        }
                    });
                              
                });
               
               //azalt
               
               //son azalt
                                
                                
                                 }//btnsepetim success
                                 });
                            });
                          });
                          
                          
                          
                         </script>
                      <!-- SEPETIM GORUNTULE JS SON -->