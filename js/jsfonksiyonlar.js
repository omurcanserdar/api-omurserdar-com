//türkçe tarih
function tr_tarih(paramtarih){
        var tarih=new Date(paramtarih);
        var gun=tarih.getDay();
        var ay=tarih.getMonth();
        var yil=tarih.getFullYear();
        var gunler= ['Pazar', 'Pazartesi', 'Salı','Çarşamba','Perşembe','Cuma','Cumartesi'];
        var aylar= ['Ocak', 'Şubat', 'Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'];
        var hours = tarih.getHours(); //returns 0-23
        var minutes = tarih.getMinutes(); //returns 0-59
        //var seconds = tarih.getSeconds(); //returns 0-59
        return tarih.getDate()+' '+aylar[ay]+' '+yil+' '+gunler[gun]+' '+hours+':'+minutes;    
}
//SON türkçe tarih

//parametre içerisine spinner ekle 
function spinekle(paramSelector){
    return $(paramSelector).html('<i class="fas fa-spinner fa-pulse fa-4x"></i> YÜKLENİYOR');
}
//son spinner ekle
function spinkaldir(paramSelector){
    return $(paramSelector).html('');
}

//parametre olarak verilen class içersindeki spinner kaldır

//son spinner kaldır

//sipariş durumuna göre progbar
function durumagoreyaz(data){
      var met="";
      if(data=="Yeni Sipariş")
          met='<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div>';
      else if(data=="Sipariş Hazırlanıyor"){
          met='<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>'+
         '<div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width: 25%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div></div>';
      }else if(data=="Gönderimde"){
        met='<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>'+
        '<div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width: 25%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>'+
        '<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: 25%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div></div>';
      }else{
         met='<div class="progress"><div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>'+
         '<div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>'+
         '<div class="progress-bar bg-warning" role="progressbar" style="width: 25%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>'+
         '<div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div></div>';
      }
      return met;
  }
//SON sipariş durumuna göre progbar

//diziden rastgele eleman çekme
function random_item(items){
     return items[Math.floor(Math.random()*items.length)];
}
//SON diziden rastgele eleman çekme

//form temizleme 
function resetForm($form) {
    $form.find('input:text, input:password, input:file, select, textarea').val('');
    $form.find('input:text, input:password, input:file, select, textarea').attr("class","form-control");
    $form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
}
//SON form temizleme

function mesajKapat(paramMesajKutu,paramsure=2000){
    window.setTimeout(function(){ paramMesajKutu.close()}, paramsure);
}

  /*
  
  //
function validateEmail(email) 
{
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
}
    
//console.log(validateEmail('anystring@anystring.anystring'));

//



  $(document).ready(function(){
          setTimeout(function(){
   window.location.reload(1);
}, 5000);
}); */
