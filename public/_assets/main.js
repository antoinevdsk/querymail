function confirmClick(){
	$('a.confirm').click(function(){
		return confirm("Do you confirm?");
	});
}

function kpiPreview(){
	$("a.kpiPreview").click(function(){
		kpiPageSubmit();
		$.ajax({
			url: '/kpi/preview',
			type: "POST",
			data: $('form#kpiForm').serialize(),
			beforeSend : function(){
				$('#preview').html('<div class="panel panel-info"><div class="panel-heading">Kpi preview</div><div class="panel-body"><i class="fa fa-spinner fa-spin"></i> LOADING...</div></div>');
			},
			success: function(msg){
				$('#preview').html('<div class="panel panel-info"><div class="panel-heading">Kpi preview</div><div class="panel-body">'+msg+'</div></div>');
			}
		});
		return false;
	});		
}

function sendMail(){
	$("a.sendMail").click(function(){
		$.ajax({
			url: $(this).attr('href'),
			beforeSend : function(){
				$('.popinFake').show();
				$('#popinFakeContent').html('<i class="fa fa-spinner fa-spin"></i> LOADING...');
			},
			success: function(msg){
				$('#popinFakeContent').html(msg);
			}
		});
		return false;
	});
}

function popin(){
	$('a.closePopinFake').click(function(){
		$('div.popinFake').hide();
		return false;
	});
}

var query;
var query_compare;

function aceEditor(){
	query = ace.edit("query");
	query.setTheme("ace/theme/monokai");
	query.getSession().setMode("ace/mode/mysql");
	query.getSession().setUseWrapMode(true);
	
	query_compare = ace.edit("query_compare");
	query_compare.setTheme("ace/theme/monokai");
	query_compare.getSession().setMode("ace/mode/mysql");
	query_compare.getSession().setUseWrapMode(true);
	
	$("#query, #query_compare").resizable();
	$("form").submit(function() {
		kpiPageSubmit();
	});
}

function kpiPageSubmit(){
	if( typeof query != "undefined" && query.getValue()  ){
		$('input[name="QUERY"]').val(query.getValue());  
	}
	if( typeof query_compare != "undefined" && query_compare.getValue()  ){
		$('input[name="QUERY_COMPARE"]').val(query_compare.getValue());       
	}
}
	
$(document).ready(function() {
	confirmClick();
	kpiPreview();
	sendMail();
	popin();
});
