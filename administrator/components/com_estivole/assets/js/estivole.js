function addDayTimeModal(daytime_id, calendar_id, daytime_day)
{
	jQuery.ajax({
		url:'index.php?option=com_estivole&task=getDaytime&tmpl=component',
		type:'POST',
		data: 'daytime_id='+daytime_id+'&calendar_id='+calendar_id+'&daytime_day='+daytime_day,
		dataType: 'JSON',
		success:function(data)
		{
			jQuery("#addDayTimeModal").modal('show');
			jQuery("#addDayTimeModal #calendar_id").val(data.calendar_id);	
			jQuery("#addDayTimeModal #daytime_day").val(data.daytime_day);			
			if(daytime_id==null){
				jQuery("#addDayTimeModal #daytime_id").val('');	
				jQuery("#addDayTimeModal #quota").val('');	
				jQuery("#addDayTimeModal #description").val('');
				jQuery("#addDayTimeModal #save_time_btn, #addDayTimeModal #myModalLabel").html('Ajouter la tranche horaire');	
			}else{
				jQuery("#addDayTimeModal #daytime_id").val(data.daytime_id);
				jQuery("#addDayTimeModal #save_time_btn, #addDayTimeModal #myModalLabel").html('Modifier la tranche horaire');	
			}
			
			jQuery("#addDayTimeModal #description").val(data.description);
			jQuery("#addDayTimeModal #quota").val(data.quota);
			jQuery("#addDayTimeModal #jformdaytime_hour_end").attr('value', data.daytime_hour_end);
			jQuery("#addDayTimeModal #jformdaytime_hour_end").trigger("liszt:updated");
			jQuery("#addDayTimeModal #jformdaytime_hour_start").attr('value', data.daytime_hour_start);
			jQuery("#addDayTimeModal #jformdaytime_hour_start").trigger("liszt:updated");
			jQuery("#addDayTimeModal #jformservice_id").attr('value', data.service_id);
			jQuery("#addDayTimeModal #jformservice_id").trigger("liszt:updated");
		},
		error : function(resultat, statut, erreur){
			alert(erreur);
		}
	});
}

function copyCalendarModal(calendar_id)
{
	jQuery.ajax({
		url:'index.php?option=com_estivole&task=getCalendar&tmpl=component',
		type:'POST',
		data: 'calendar_id='+calendar_id,
		dataType: 'JSON',
		success:function(data)
		{
			jQuery("#copyCalendarModal").modal('show');
			jQuery("#copyCalendarModal #calendar_id").val(calendar_id);
			jQuery("#copyCalendarModal #description").val(data.description);
			jQuery("#copyCalendarModal #name").val(data.name);
		},
		error : function(resultat, statut, erreur){
			alert(erreur);
		}
	});
}

function exportModal(daytime_id)
{
	jQuery.ajax({
		url:'index.php?option=com_estivole&task=getDaytime&tmpl=component',
		type:'POST',
		data: 'daytime_id='+daytime_id,
		dataType: 'JSON',
		success:function(data)
		{
			jQuery("#addDayTimeModal").modal('show');

			if(daytime_id==''){
				jQuery("#addDayTimeModal #daytime_id").val('');	
				jQuery("#addDayTimeModal #quota").val('');	
				jQuery("#addDayTimeModal #description").val('');	
			}else{
				jQuery("#addDayTimeModal #daytime_id").val(data.daytime_id);
			}

			jQuery("#addDayTimeModal #description").val(data.description);
			jQuery("#addDayTimeModal #quota").val(data.quota);
			jQuery("#addDayTimeModal #jformdaytime_hour_end").attr('value', data.daytime_hour_end);
			jQuery("#addDayTimeModal #jformdaytime_hour_end").trigger("liszt:updated");
			jQuery("#addDayTimeModal #jformdaytime_hour_start").attr('value', data.daytime_hour_start);
			jQuery("#addDayTimeModal #jformdaytime_hour_start").trigger("liszt:updated");
			jQuery("#addDayTimeModal #jformservice_id").attr('value', data.service_id);
			jQuery("#addDayTimeModal #jformservice_id").trigger("liszt:updated");
		},
       error : function(resultat, statut, erreur){
			alert(erreur);
       }
	});
}

function addAvailibilityModal(member_id, member_daytime_id)
{
	jQuery("#addAvailibilityModal").modal('show');
	jQuery("#addAvailibilityModal #member_id").val(member_id);
	jQuery("#addAvailibilityModal #member_daytime_id").val(member_daytime_id);
	var calendar_id = jQuery("#calendar_selector").val();
	getCalendarDates(calendar_id);
	jQuery("#jformdaytime").chosen().change( function(){
		var daytime = jQuery(this).val();
		var service_id = jQuery("#jformservice_id").val();
		getCalendarDaytimes(calendar_id, daytime, service_id);
	});
	jQuery("#jformservice_id").chosen().change( function(){
		var service_id = jQuery(this).val();
		var daytime = jQuery("#jformdaytime").val();
		getCalendarDaytimes(calendar_id, daytime, service_id);
	});
}

function composeEmail(member_id, member_daytime_id)
{
	jQuery("#composeEmail").modal('show');
	jQuery("#composeEmail #member_id").val(member_id);
	jQuery("#composeEmail #member_daytime_id").val(member_daytime_id);
	var calendar_id = jQuery("#calendar_selector").val();
	getCalendarDates(calendar_id);
	jQuery("#jformdaytime").chosen().change( function(){
		var daytime = jQuery(this).val();
		var service_id = jQuery("#jformservice_id").val();
		getCalendarDaytimes(calendar_id, daytime, service_id);
	});
	jQuery("#jformservice_id").chosen().change( function(){
		var service_id = jQuery(this).val();
		var daytime = jQuery("#jformdaytime").val();
		getCalendarDaytimes(calendar_id, daytime, service_id);
	});
}

function deleteAvailibility(member_daytime_id)
{
	jQuery.ajax({
		url:'index.php?option=com_estivole&controller=member&task=member.deleteAvailibility',
		type:'POST',
		data: 'member_daytime_id='+member_daytime_id,
		dataType: 'JSON',
		success:function(data)
		{
			if(data.success)
			{
				alert('ok');
			} else {
				alert('ko');
			}
		},
       error : function(resultat, statut, erreur){
			alert(erreur);
       }
	});
}

function getCalendarDates(calendar_id)
{
	jQuery.ajax({
		url:'index.php?option=com_estivole&task=getCalendarDates&format=raw',
		type:'POST',
		data: 'calendar_id='+calendar_id,
		dataType: 'JSON',
		success:function(data)
		{
			if(data.success)
			{
				var el = jQuery("#addAvailibilityModal #jformdaytime");
				el.empty(); // remove old options
				for(i=0;i<data.calendar_dates.length;i++){
					el.append(jQuery("<option></option>").attr("value", data.calendar_dates[i].daytime_day).text(data.calendar_dates[i].daytime_day));
				}
				jQuery("#addAvailibilityModal #jformdaytime").trigger("liszt:updated");
				var service_id = jQuery("#jformservice_id").val();
				var daytime = jQuery("#jformdaytime").val();
				getCalendarDaytimes(calendar_id, daytime, service_id);	
			} else {

			}
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
			var el = jQuery("#addAvailibilityModal #availibilityTableDiv");
			el.html(data);
		},
       error : function(resultat, statut, erreur){
			alert(erreur);
       }
	});
}

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
				var daytime = jQuery("#addDayTimeForm #jformdaytime").val();
				var service_id = jQuery("#addDayTimeForm #jformservice_id").val();
				var calendar_id = jQuery("#addDayTimeForm #jformcalendar_id").val();
				getCalendarDaytimes(calendar_id, daytime, service_id);
		},
       error : function(resultat, statut, erreur){
			alert(erreur);
       }
	});
}