</main>

<footer class="text-muted">
      <a class="top btn btn-outline-info btn-sm mt-2 mb-2 float-right mr-3" href="javascript:void(0)"><i class="fa fa-arrow-circle-up" aria-hidden="true"></i> yukarı çık</a>
    
  <!--  <p> ömürcan serdar </p> -->
</footer>

      <!-- YUKARI ÇIK BUTON -->
<script>
$(document).ready(function() {
    $('.top').on("click",function(){
	$('html,body').animate({ scrollTop:0},500); //0 en yukarı 500 yarım saniyede çık 
    });
    
    $( ".top" ).mouseover(
  function() {
    $( this ).css( "font-size", "18px" );
  });
   $( ".top" ).mouseout(
  function() {
    $( this ).css( "font-size", "12px" );
  });
});
</script>

<!-- YUKARI ÇIK BUTON BİTİŞ--> 



</body>
</html>