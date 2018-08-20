/**
 * Created by Azamat on 20.12.2015.
 */
$('#headers-id_city').chosen({no_results_text:'Нет результатов поиска'});
$('#city_choose').chosen({no_results_text:'Нет результатов поиска'});

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