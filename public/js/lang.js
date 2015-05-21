var Data = Array();

function translate()
{
    getLanguageData(function(data) {   
        
        if(data.lang === 'uk')
        {
            Data['lang'] = data.lang;
            Data['price'] = 'Вартість';
            Data['distance'] = 'Відстань';
            Data['stop_start'] = 'Сідати на зупинці';
            Data['stop_end'] = 'Ставати на зупинці';
            Data['on_the_road'] = 'В дорозі';
            Data['min'] = 'хв.';
            Data['walk_to'] = 'Пішки до зупинки';
            Data['walk_from'] = 'Пішки від зупинки';
        }     
        if(data.lang === 'ru')
        {
            Data['lang'] = data.lang;
            Data['price'] = 'Стоимость';
            Data['distance'] = 'Расстояние';
            Data['stop_start'] = 'Садиться на остановке';
            Data['stop_end'] = 'Вставать на остановке';
            Data['on_the_road'] = 'В дороге';
            Data['min'] = 'мин.';
            Data['walk_to'] = 'Пешком до остановки';
            Data['walk_from'] = 'Пешком от остановки';
        } 
        if(data.lang === 'en')
        {
            Data['lang'] = data.lang;
            Data['price'] = 'Price';
            Data['distance'] = 'Distance';
            Data['stop_start'] = 'Planted at the station';
            Data['stop_end'] = 'Replace the station';
            Data['on_the_road'] = 'On the road';
            Data['min'] = 'min.';
            Data['walk_to'] = 'A walk to the bus stop';
            Data['walk_from'] = 'Walk from the bus stop';
        } 
    });
}

function getLanguageData(callback)
{
    $.ajax({        
        dataType: 'json',                
        type: 'GET',
        url: '/rest/getLanguage',                       
        success: function(response)
        {            
            callback(response);            
        }
    }); 
}