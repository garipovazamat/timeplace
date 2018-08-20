/**
 * Created by Azamat on 20.12.2015.
 */
$('.reg.open-popup').click(function(){
    setTimeout(function(){
        $('#city_reg').chosen({no_results_text:'Нет результатов поиска'});
    }, 500)
});
$('.city.open-popup').click(function(){
    setTimeout(function(){
        $('#city_choose').chosen({no_results_text:'Нет результатов поиска'});
    }, 500)
});
$('#city').chosen({no_results_text:'Нет результатов поиска'});
$('#eventcategory-id_category').chosen({no_results_text:'Нет результатов поиска'});
$('#message-receiver').chosen({no_results_text:'Нет результатов поиска'});
$('#city_event').chosen({no_results_text:'Нет результатов поиска'});


function sendGet(url, success){
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.send();
    xhr.onreadystatechange = function() {
        if (this.readyState != 4) {
            var data = this.response;
            success(data);
        }
    }
}

