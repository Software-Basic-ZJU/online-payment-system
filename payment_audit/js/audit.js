$('#yifu').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
  loadTable(0)
})


$('#weifu').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
  
  loadTable(1)
})

$('#weifa').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
   loadTable(2)
})

$('#tuikuan').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
   loadTable(3)
})


$('#shentui').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
   loadTable(6)
})



$('#shensu').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
   loadTable(7)
})


$('#succes').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
   loadTable(4)
})

$('#fail').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
   loadTable(5)
})

$(function(){
   loadTable(0);
});

function loadTable(intq) {
	$.ajax({
		url:"./php/audit.php",
		data :{"intq":intq},
		success:function(res){
			console.log(res);
		    $('#tb1').html(res);
		}
	});
}
