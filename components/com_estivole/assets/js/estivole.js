function getDaytimesByService(calendar_id, service_id)
{
	jQuery.ajax({
		url:'index.php?option=com_estivole&controller=daytime&task=daytime.getDaytimesByService&tmpl=component',
		type:'POST',
		data: 'calendar_id='+calendar_id+'&service_id='+service_id,
		dataType:'JSON',
		success:function(data)
		{
			jQuery("#addDayTimeForm #jformdaytime").empty();
			jQuery("#addDayTimeForm #jformdaytime").trigger('liszt:updated');
			jQuery.each(data, function(index, item) {
				var formattedDate = new Date(item.daytime_day);
				var d = formattedDate.getDate();
				if(d<10)d='0'+d;
				var m =  formattedDate.getMonth();
				m += 1;  // JavaScript months are 0-11
				if(m<10)m='0'+m;
				var y = formattedDate.getFullYear();
				formattedDate = d + "-" + m + "-" + y;
				
				jQuery("#addDayTimeForm #jformdaytime").append(jQuery('<option></option>').val(item.daytime_day).text(formattedDate));
			});
			jQuery("#addDayTimeForm #jformdaytime").trigger('liszt:updated');
			
			var daytime = jQuery("#addDayTimeForm #jformdaytime").chosen().val();
			var service_id = jQuery("#addDayTimeForm #jformservice_id").chosen().val();
			var calendar_id = jQuery("#addDayTimeForm #calendar_id").val();
			getCalendarDaytimes(calendar_id, daytime, service_id);
		},
       error : function(resultat, statut, erreur){
			alert(erreur);
       }
	});
}

function getCalendarDaytimes(calendar_id, daytime, service_id)
{
	var member_id = jQuery("#addDayTimeForm #member_id").val();

	jQuery.ajax({
		url:'index.php?option=com_estivole&controller=member&view=member&layout=_availibilitytable&format=raw&tmpl=component',
		type:'POST',
		data: 'calendar_id='+calendar_id+'&daytime='+daytime+'&service_id='+service_id+'&member_id='+member_id,
		success:function(data)
		{
			var el = jQuery("#addDayTimeForm #availibilityTableDiv");
			el.html(data);
		},
       error : function(resultat, statut, erreur){
			alert(erreur);
       }
	});
}