<?php
 $title="Şifremi Unuttum | API";
 $desc="Şifremi Unuttum";
 include "header.php";
 ?>
 <form>
     
     <div class="container">
         <h1 class="text-danger">Şifre İşlemleri<small class="text-info"> işlem yapılacak üyelik tipini ve üyelik email adresini belirtin</small></h1><hr>
     
     
    <div class="col-md-6 mx-auto">
        <div class="card">
          <div class="card-header bg-primary text-white text-center"><i class="fas fa-edit"></i> Şifre İşlem</div>
          <div class="card-body float-left">
              
              <div class="form-group">
     
        <label for="formGroupExampleInput">Üyelik Tipi:  </label>
     
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="radiouyetip" checked value="bireysel">
            <label class="form-check-label" for="inlineRadio1">Bireysel</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="radiouyetip" value="kurumsal">
            <label class="form-check-label" for="inlineRadio2">Kurumsal</label>
        </div>
      
    </div>
    
    <div class="form-group">
        
        <div class="form-row">
            <label for="formGroupExampleInput">Üye Email:  </label>  
            <div class="col">
                <input type="email" class="form-control" placeholder="Email" id="iemail" value="omurserdarr@gmail.com">
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <button type="button" id="btnsifreuyesorgu" class="mt-1 btn btn-info btn-block"> Gönder</button>
    </div>
            
          </div>
        </div>
    </div>
    
 </form>
 
 </div>
 
 <script>
     $(function (){
     $('#btnsifreuyesorgu').click(function(){
    //var dkilan_id = $(this).attr('id');
    
    /*var veri=$("#loginform").serialize();
     $.alert({
        columnClass: 'col-md-6 col-md-offset-3',
        type: 'blue',
        title: 'Giriş İşlemi',
        content: 'Gönderilen name alanları ve değerleri: '+veri,
        });
    return false;
    */
     //var dsecim='btngirisyap';
     //var dsif=$('#password1').val();
     //var dra=document.querySelector('input[name="radiogiris"]:checked').value;
     var dra=$("input[name='radiouyetip']:checked").val();
     var dad=$('#iemail').val();
     
     /*
     $('input:radio[name="radiouyetip"]').change(function() { //bireysel-kurumsal radio değişimi olduğunda
    
        if ($(this).val() == 'bireysel') { //bireysel secili ise
           dad="bireysel";
        }
        else{ //kurumsal secili ise
           dad="kurumsal";
        }
    });
     */
     
    var secimhatadurum="";
    
    if(!dra)
        secimhatadurum+="Üyelik Tipi ";
    if(!dad)
        secimhatadurum+="Email";
    
    if(!dra||!dad){
        var diBos=$.dialog('<b class="text-danger">'+secimhatadurum+' boş geçilemez</b>');
        mesajKapat(diBos);
        return false;
    }
    //else
        //console.log(dra+dad);
    
        
      $.ajax({
     method: 'GET',
     url : "https://www.api.omurserdar.com/api/"+dra,
     data : {email:dad},
      beforeSend: function() {
        //$(".loader").show();
    },
     success:function(cevap){
         if(cevap.hata==false){
             
            if(dra=="bireysel"){
                var data=cevap.bireyselbilgileri;
                var puturl="https://www.api.omurserdar.com/api/bireysel/";
             }
            else{
                var data=cevap.kurumsalbilgileri;
                var puturl="https://www.api.omurserdar.com/api/kurumsal/";
            }   
            
            var jcConfirmSifGonderUyeVar=$.confirm({
        type: 'orange',
        title: ' Şifre Değişikliği ',
        content: "<b class='badge badge-primary'>"+data.ad+"</b> yeni şifreniz email olarak iletilsin mi? ",
        onOpenBefore: function () {
                        jcConfirmSifGonderUyeVar.showLoading();
                    },
        onContentReady: function () {
                        jcConfirmSifGonderUyeVar.hideLoading();
                    },
                    
                buttons: {
                    evet: {
                        text: 'Email Gönder',
                        btnClass: 'btn-danger',
                        action: function () {
                            
                            var dobje={"email":dad,"sifre":"ok"};
                            dobje=JSON.stringify(dobje);
                            
                            
                                $.ajax({
                                method: 'PUT',
                                url : puturl,
                                data: dobje,
                                success: function(apisifretamam){
                                    
                                    if(apisifretamam.mesaj=="güncellendi"){
                                        var diaSifregonderildiMail=$.dialog({
                                            closeIcon:false,
                                            type:'info',    
                                            title: '<b class="text-info"          >Mail Gönderildi</b>',
                                            content:  "<p class='lead font-weight-bolder'> Şifre rastgele oluşturulup email adresinize gönderilmiştir </p>",
                                        });
                                        mesajKapat(diaSifregonderildiMail,4000);
                                        
                                    }
                                }//GÜNCELLE SUCCESS
                                 });
                                 
                        }
                    }, //EMAİL GONDER BTN
                    cancel: {
                         text:'İptal Et'
                    }
                }
                });
            
         }// SON GET HATA==FALSE 
        
        else{
            var jcSifGonderHataVar=$.dialog({
            type: 'red',
            title: ' Başarısız İşlem ',
            content: cevap.hataMesaj,
            onOpenBefore: function () {
                            jcSifGonderHataVar.showLoading();
                        },
            onContentReady: function () {
                            jcSifGonderHataVar.hideLoading();
                        },
                    });
        }
    },//success
    complete:function(data){
    //$(".loader").hide();
            }
        });
       });
    });
              </script>
    <!-- LOGIN JS SON -->
     
 </script>
 
 <?php include "footer.php" ?>