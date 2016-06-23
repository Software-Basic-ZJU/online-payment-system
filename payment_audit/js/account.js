$('#tongguo').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
   loadTable(1)
})

$('#weiguo').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
   loadTable(0)
})

$(function(){
	loadTable(1);
})

function loadTable(intq) {
	$.ajax({
		url:"./php/account.php",
		data :{"intq":intq},
		success:function(res){
		    $('#tb1').html(res);
		}
	});
}
