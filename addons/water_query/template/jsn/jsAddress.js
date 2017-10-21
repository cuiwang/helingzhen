//selectinit(city,area,'cmbCity','cmbArea');

function selectinit(c,a,city,area){
  $('#' + city).change(function(){selectarea(city,area);});
  var k = '';
  $.each(cityArr,function(n,value){
   if(c==value){
    $('#' + city).append('<option value="' + value + '" selected="true" >' + value + '</option>');
    k = n;
  }else{
    $('#' + city).append('<option value="' + value + '">' + value + '</option>');
  }
});
  if(a!=''){
    $('#' + area).empty();
    $.each(areaArr[k],function(n,value){
      if(value == a){
        $('#' + area).append('<option value="' + value + '"  selected="true" >' + value + '</option>');
      }else{
        $('#' + area).append('<option value="' + value + '">' + value + '</option>');
      }
    });
  }
}
function selectarea(city,area){
  $('#' + area).empty();
  $.each(areaArr[$('#' + city).get(0).selectedIndex],function(n,value){
    $('#' + area).append('<option value="' + value + '">' + value + '</option>');
  });
}